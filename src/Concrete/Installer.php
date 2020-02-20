<?php 
namespace Concrete\Package\SkybluesofaPageListPlus;

use Concrete\Core\Foundation\ConcreteObject;
use Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use Concrete\Core\Attribute\Set as AttributeSet;
use Concrete\Core\Attribute\Type as AttributeType;
use Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Concrete\Core\Page\Page as Page;
use Concrete\Core\Block\BlockType\BlockType as BlockType;
use Concrete\Core\Block\BlockType\Set as BlockTypeSet;
use Concrete\Core\Page\Single as SinglePage;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Filter;
use Database;
use Config;
use \Symfony\Component\ClassLoader\MapClassLoader as SymfonyMapClassloader;
use Concrete\Core\Package\PackageList;
use Package;

defined('C5_EXECUTE') or die("Access Denied.");

class Installer extends ConcreteObject
{

    protected static $collectionAttributes = [];

    protected static $singlePages = [
        ['path' => '/dashboard/page_list_plus', 'name' => 'Page List+', 'description' => 'Page Lists with keyword and page attribute filtering as well as built-in list titles'],
        ['path' => '/dashboard/page_list_plus/attribute_blacklist', 'name' => 'Attribute Blacklist', 'description' => 'Exclude page attributes from Page List+'],
        ['path' => '/dashboard/page_list_plus/area_blacklist', 'name' => 'Area Blacklist', 'description' => 'Exclude page areas from Page List+'],
    ];

    protected static $blockTypes = [
        ['handle' => 'page_list_plus', 'set' => 'navigation'],
    ];

    public static $pageListPlusFiltersPropertyName = 'pageListPlusFilters';

    public static function preInstallationCheck()
    {
        $errors = [];

        if (count($errors) > 0) {
            $exception = '';
            foreach ($errors as $error) {
                $exception .= '<p><b>' . $error[0] . '</b><br>' . $error[1] . '</p>';
            }
            throw new Exception($exception);
        } else {
            return false;
        }
    }

    static function install($pkg)
    {
        self::installConfigs($pkg);
        self::installCollectionAttributes($pkg);
        self::installSinglePages($pkg);
        self::installBlocks($pkg);
        self::refreshFilters($pkg);
    }

    static function upgrade($pkg)
    {
        self::installConfigs($pkg);
        self::installCollectionAttributes($pkg);
        self::installSinglePages($pkg);
        self::installBlocks($pkg);
        self::refreshFilters($pkg);
    }

    static function uninstall($pkg)
    {
        self::uninstallFilters($pkg);
    }

    private static function installConfigs($pkg) {
        if (!$pkg->getConfig()->get('date_picker.format')) {
            $pkg->getConfig()->save('date_picker.format', 'mm/dd/yy');
        }
        if (!$pkg->getConfig()->get('date_field.format')) {
            $pkg->getConfig()->save('date_field.format', 'm/d/Y');
        }
    }
    private static function installCollectionAttributes($pkg)
    {
        $attributeKeyCategory = AttributeKeyCategory::getByHandle('collection');
        $attributeSet = AttributeSet::getByHandle('page_list_plus');
        if (!is_object($attributeSet)) {
            $attributeSet = $attributeKeyCategory->addSet('page_list_plus', t('Page List+'), $pkg);
        }
        foreach (Installer::$collectionAttributes as $collectionAttributeHandle => $meta) {
            //$attributeType = AttributeType::getByHandle($meta['type']);
            $properties = ['akHandle' => $collectionAttributeHandle, 'akName' => t($meta['name'])];
            if (isset($meta['properties'])) {
                $properties = array_merge($properties, $meta['properties']);
            }

            $collectionAttribute = CollectionAttributeKey::getByHandle($collectionAttributeHandle);
            if (!is_object($collectionAttribute)) {
                $collectionAttribute = CollectionAttributeKey::add($meta['type'], $properties, $pkg);
                $collectionAttribute->setAttributeSet($attributeSet);;
            } else {
                $collectionAttribute->update($properties);
            }
        }
    }

    private static function installSinglePages($pkg)
    {
        $db = Database::getActiveConnection();

        foreach (Installer::$singlePages as $singlePage) {
            $page = Page::getByPath($singlePage['path']);
            if ($page->cID == 0) {
                $newPage = SinglePage::add($singlePage['path'], $pkg);
                $newPage->update(array('cName' => t($singlePage['name']), 'cDescription' => t($singlePage['description'])));
                $db->query("UPDATE Pages SET cFilename=? WHERE cID = ?", [$singlePage['path'] . '.php', $newPage->cID]);
            }
        }
    }

    private static function installBlocks($pkg)
    {
        foreach (Installer::$blockTypes as $blockType) {
            $existingBlockType = BlockType::getByHandle($blockType['handle']);
            if (!$existingBlockType) {
                BlockType::installBlockTypeFromPackage($blockType['handle'], $pkg);
            }
            if (isset($blockType['set']) && $blockType['set']) {
                $navigationBlockTypeSet = BlockTypeSet::getByHandle('navigation');
                if ($navigationBlockTypeSet) {
                    $navigationBlockTypeSet->addBlockType(BlockType::getByHandle($blockType['handle']));
                }
            }
        }
    }

    public static function refreshFilters()
    {
        /*
         * This method expects that any package that wants to install Page List+ filters have
         * a static property existent on the package controller. This property can be one of these:
         *
         * 1. A string. In the instance that only one filter is being installed by the package, it
         *    can be a string like this:
         *
         *    public static $pageListPlusFilters = 'my_filter';
         *
         * 2. A numerically indexed array. In this instance, multiple filters will be installed:
         *
         *    public static $pageListPlusFilters = ['my_filter', 'my_other_filter'];
         *
         * 3. A text-indexed array. In this instance, multiple filters will be installed:
         *
         *    public static $pageListPlusFilters = [
         *       'my_filter'=>'MyFilter',
         *       'my_other_filter'=>'MyOtherFilter'
         *    ];
         *
         * In any case, Page List+ will create a snake_cased handle and CamelCased name.
         */
        $db = Database::getActiveConnection();
        $db->execute("DELETE FROM aoPageListPlusFilterPlugins WHERE 1=1");

        $installedPackages = PackageList::get();
        foreach ($installedPackages->getPackages() as $installedPackage) {
            if (self::existingPackageHasInstallableFilters($installedPackage)) {
                self::installFiltersForExistingPackage($installedPackage);
            }
        }
    }

    private function existingPackageHasInstallableFilters($existingPackage)
    {
        $packageController = self::getPackageClass($existingPackage);
        if (property_exists($packageController, self::$pageListPlusFiltersPropertyName)) {
            $pageListPlusFiltersProperty = new \ReflectionProperty($packageController, self::$pageListPlusFiltersPropertyName);
            if ($pageListPlusFiltersProperty->isStatic()) {
                return true;
            }
        }
        return false;
    }

    private function installFiltersForExistingPackage($existingPackage)
    {
        $packageController = new \ReflectionClass(self::getPackageClass($existingPackage));
        $pageListPlusFilters=$packageController->getStaticPropertyValue(self::$pageListPlusFiltersPropertyName);
        if (!$pageListPlusFilters) {
            return;
        }
        if (!is_array($pageListPlusFilters)) {
            $pageListPlusFilters = [$pageListPlusFilters];
        }

        $th = \Core::make("helper/text");
        foreach ($pageListPlusFilters as $handle => $filterName) {
            $handle = $th->uncamelcase(is_numeric($handle) ? $filterName : $filterName);
            $filterName = $th->CamelCase($filterName);
            Filter::add($handle, $filterName, $existingPackage);
        }
    }

    private static function getPackageClass($package)
    {
        return Package::getClass($package->getPackageHandle());
    }

    public static function uninstallFilters($package)
    {
        $db = Database::getActiveConnection();
        $databaseParameters = $db->getParams();
        $sql = "select table_name from information_schema.tables where table_name=? AND table_schema=?";
        if ($db->getOne($sql, ['aoPageListPlusFilterPlugins', $databaseParameters['database']])) {
            $filtersInPackage = Filter::getForPackage($package);
            foreach ($filtersInPackage as $filterInPackage) {
                $filterInPackage->delete();
            }
        }
    }
}
