<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter;

use Concrete\Core\Foundation\ConcreteObject;
use \Concrete\Core\Attribute\Type as AttributeType;
use \Doctrine\DBAL\Query\QueryBuilder;
use Database;
use Core;
use Package;

defined('C5_EXECUTE') or die("Access Denied.");

class Filter extends ConcreteObject
{
    private $filterClass = null;
    protected $packageId = null;
    protected $handle = null;
    protected $filterName = null;

    public function getPackageId() {
        return $this->packageId;
    }
    public static function add($handle, $name, $package = null)
    {
        $db = Database::getActiveConnection();
        $packageId = 0;
        $filter = null;
        if (!is_null($package)) {
            $packageId = $package->getPackageID();
        }
        if (self::canInstallFilterWithHandle($handle)) {
            $values = [$handle, $name, $packageId];
            $db->Execute("INSERT INTO aoPageListPlusFilterPlugins (handle, filterName, packageId) VALUES (?,?,?)", $values);
            $filter = self::getByHandle($handle);
        }
        return $filter;
    }

    public static function getById($id)
    {
        $db = Database::getActiveConnection();
        $data = $db->GetRow("SELECT * FROM aoPageListPlusFilterPlugins WHERE id=?", $id);
        $filter = null;
        if (!empty($data)) {
            $filter = new Filter();
            $filter->setPropertiesFromArray($data);
            $filter->setFilterClass();
        }
        return ($filter instanceof Filter) ? $filter : false;
    }

    public static function getForPackage($package) {
        $db = Database::getActiveConnection();
        $rs = $db->execute("SELECT id FROM aoPageListPlusFilterPlugins WHERE packageId=?", $package->getPackageID());
        $filters = [];
        while ($row = $rs->FetchRow()) {
            $filters[] = Filter::getById($row['id']);
        }
        return $filters;
    }

    public function getClass()
    {
        if ($this->filterClass) {
            return $this->filterClass;
        }
        return false;
    }

    protected function setFilterClass()
    {
        $th = \Core::make("helper/text");
        $packageName = $th->camelcase(Package::getByID($this->packageId)->getPackageHandle());
        $className = $th->camelcase($this->handle);
        $class = "Concrete\\Package\\" . $packageName . "\\PageListPlus\\Filter\\" . $className;
        $this->filterClass = Core::make($class);
    }

    public function getFilterClass()
    {
        return $this->filterClass;
    }

    public static function getByHandle($handle)
    {
        /*
         * First, we'll see if another package has overwritten a core filter. If we don't find
         * that is true, then we'll attempt to load the core filter, if it exists.
         */
        $db = Database::getActiveConnection();
        $pageListPlusPackage = self::getCorePackage();
        $sql = "SELECT id FROM aoPageListPlusFilterPlugins WHERE handle=? and packageId!=? ORDER BY id DESC";
        $filter = $db->GetRow($sql, [$handle, $pageListPlusPackage->getPackageID()]);
        if (!isset($filter['id']) || !$filter['id']) {
            $sql = "SELECT id FROM aoPageListPlusFilterPlugins WHERE handle=? and packageId=?";
            $filter = $db->GetRow($sql, [$handle, $pageListPlusPackage->getPackageID()]);
        }
        return self::getById($filter['id']);
    }

    public function delete()
    {
        $db = Database::getActiveConnection();
        $sql = "DELETE FROM aoPageListPlusFilterPlugins WHERE id=?";
        $db->Execute($sql, [$this->id]);
    }

    private static function getCorePackage()
    {
        return Package::getByHandle('skybluesofa_page_list_plus');
    }

    private static function canInstallFilterWithHandle($handle = null)
    {
        $filter = self::getByHandle($handle);
        $pageListPlusPackage = self::getCorePackage();
        if (!($filter instanceof Filter)) {
            return true;
        }
        if (($filter instanceof Filter) && ($filter->packageId == $pageListPlusPackage->getPackageID())) {
            return true;
        }
        return false;
    }
}
