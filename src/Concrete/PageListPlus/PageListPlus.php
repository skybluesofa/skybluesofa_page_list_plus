<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\PageListPlus;

use Concrete\Core\Page\PageList;
use Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Concrete\Core\File\FileList;
use Concrete\Core\Page\Page;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Generator;
use Database;
use Concrete\Core\Page\Collection\Version\Version as CollectionVersion;

defined('C5_EXECUTE') or die("Access Denied.");

/**
 *
 * An object that allows a filtered list of pages to be returned.
 *
 */
class PageListPlus extends PageList
{

    static $sortingOptions = [
        'text' => ['az' => 'A-Z', 'za' => 'Z-A'],
        'textarea' => ['az' => 'A-Z', 'za' => 'Z-A'],
        'boolean' => ['az' => 'False then True', 'za' => 'True then False'],
        'date_time' => ['az' => 'Old-New', 'za' => 'New-Old'],
        'image_file' => ['az' => 'A-Z', 'za' => 'Z-A'],
        'email' => ['az' => 'A-Z', 'za' => 'Z-A'],
        'telephone' => ['az' => 'A-Z', 'za' => 'Z-A'],
        'url' => ['az' => 'A-Z', 'za' => 'Z-A'],
        'number' => ['az' => 'Low-High', 'za' => 'High-Low'],
        'rating' => ['az' => 'Low-High', 'za' => 'High-Low'],
        'select' => ['az' => 'A-Z', 'za' => 'Z-A'],
        'page_selector' => ['az' => 'A-Z', 'za' => 'Z-A'],
        'user_selector' => ['az' => 'A-Z', 'za' => 'Z-A'],
        'associated_page' => ['az' => 'A-Z', 'za' => 'Z-A'],
        'coordinates' => ['az' => 'Low-High', 'za' => 'High-Low'],
        'ad_rate' => ['az' => 'Low-High', 'za' => 'High-Low'],
        'coordinate_limit_radius' => ['az' => 'Low-High', 'za' => 'High-Low'],
    ];
    public $settings;
    public $selection;

    // Page Selection Tab
    public $pageTypeId = [];
    public $parentPageId = 'EVERYWHERE';
    public $parentPageIdValue = 0;
    public $includeAllDescendents = false;
    public $ignorePermissions = false;

    // Search Tab
    public $useForSearch = false;
    public $showSearchForm = false;
    public $showSearchBox = true;
    public $searchButtonLabel = 'Search';
    public $searchBoxTargetURL = false;
    public $submitOnChangeOfFilter = false;
    public $submitViaAjax = true;
    public $showSearchFilters = false;
    public $showSearchResults = false;
    public $numberOfResults = 0;
    public $skipTopNumberOfResults = 0;
    private $fullTextKeywords = null;

    //Display Tab
    private $inBooleanMode = false;
    private $expandSearchQueries = false;
    private $inNaturalLanguageMode = false;
    private $rssFeedTitle = null;
    private $rssFeedDescription = null;

    static function generate($settings = [])
    {
        $generator = new Generator($settings);
        $generator->generate();
        return $generator->pageListPlus;
    }

    static function getSortingOptions()
    {
        return self::$sortingOptions;
    }

    static function getSupportedAttributeTypes()
    {
        $db = Database::getActiveConnection();
        return $db->GetCol("SELECT DISTINCT(handle) AS handle FROM aoPageListPlusFilterPlugins");
    }

    static function getPageAttributeBlacklist()
    {
        $db = Database::getActiveConnection();
        $q = $db->Execute("SELECT * FROM aoPageListPlusAttributeBlacklist");
        $pageAttributeBlacklist = [];
        while ($r = $q->FetchRow()) {
            $pageAttributeBlacklist[$r['akHandle']] = $r['blacklist'];
        }
        return $pageAttributeBlacklist;
    }

    static function getPageAreaBlacklist()
    {
        $db = Database::getActiveConnection();
        $q = $db->Execute("SELECT * FROM aoPageListPlusAreaBlacklist");
        $pageAreaBlacklist = [];
        while ($r = $q->FetchRow()) {
            $pageAreaBlacklist[$r['area']] = $r['area'];
        }
        return $pageAreaBlacklist;
    }

    function debug($boolean=true) {
        $this->debug = $boolean ? true : false;
    }

    public function setBooleanMode($inBooleanMode = true)
    {
        $this->inBooleanMode = $inBooleanMode ? true : false;
    }

    public function setExpansionMode($expandSearchQueries = true)
    {
        $this->expandSearchQueries = $expandSearchQueries ? true : false;
    }

    public function setNaturalLanguageMode($inNaturalLanguageMode = true)
    {
        $this->inNaturalLanguageMode = $inNaturalLanguageMode ? true : false;
    }

    public function setFulltextSearch($fullTextSearch = true)
    {
        $this->isFulltextSearch = $fullTextSearch;
    }

    public function sortBy($column, $direction = 'asc')
    {
        if (!$column) {
            return;
        }
        $this->query->addOrderBy($column, $direction);
    }

    public function finalizeQuery(\Doctrine\DBAL\Query\QueryBuilder $query)
    {
        if ($this->includeAliases) {
            $query->from('Pages', 'p')
                ->leftJoin('p', 'Pages', 'pa', 'p.cPointerID = pa.cID')
                ->leftJoin('p', 'PagePaths', 'pp', 'p.cID = pp.cID and pp.ppIsCanonical = true')
                ->leftJoin('pa', 'PageSearchIndex', 'psi', 'psi.cID = if(pa.cID is null, p.cID, pa.cID)')
                ->leftJoin('p', 'PageTypes', 'pt', 'pt.ptID = if(pa.cID is null, p.ptID, pa.ptID)')
                ->leftJoin('p', 'CollectionSearchIndexAttributes', 'csi', 'csi.cID = if(pa.cID is null, p.cID, pa.cID)')
                ->innerJoin('p', 'CollectionVersions', 'cv', 'cv.cID = if(pa.cID is null, p.cID, pa.cID) and cvIsApproved = 1')
                ->innerJoin('p', 'Collections', 'c', 'p.cID = c.cID')
                ->andWhere('p.cIsTemplate = 0 or pa.cIsTemplate = 0');
        } else {
            $query->from('Pages', 'p')
                ->leftJoin('p', 'PagePaths', 'pp', '(p.cID = pp.cID and pp.ppIsCanonical = true)')
                ->leftJoin('p', 'PageSearchIndex', 'psi', 'p.cID = psi.cID')
                ->leftJoin('p', 'PageTypes', 'pt', 'p.ptID = pt.ptID')
                ->leftJoin('c', 'CollectionSearchIndexAttributes', 'csi', 'c.cID = csi.cID')
                ->innerJoin('p', 'Collections', 'c', 'p.cID = c.cID')
                ->innerJoin('p', 'CollectionVersions', 'cv', 'p.cID = cv.cID and cvIsApproved = 1')
                ->andWhere('p.cPointerID < 1')
                ->andWhere('p.cIsTemplate = 0');
        }

        if ($this->isFulltextSearch) {
            $query->addSelect('match(psi.cName, psi.cDescription, psi.content) against (:fulltext1' . $this->getFulltextQueryModifiers() . ') as cIndexScore');
            $query->setParameter('fulltext1', $this->fullTextKeywords);
        }

        if (!$this->includeInactivePages) {
            $query->andWhere('p.cIsActive = :cIsActive');
            $query->setParameter('cIsActive', 1);
        }
        if (!$this->includeSystemPages) {
            $query->andWhere('p.cIsSystemPage = :cIsSystemPage');
            $query->setParameter('cIsSystemPage', 0);
        }
        return $query;
    }

    private function getFulltextQueryModifiers()
    {
        $queryModifiers = "";
        if ($this->inBooleanMode) {
            $queryModifiers .= " IN BOOLEAN MODE";
        } else {
            if ($this->inNaturalLanguageMode) {
                $queryModifiers .= " IN NATURAL LANGUAGE MODE";
            }
            if ($this->expandSearchQueries) {
                $queryModifiers .= " WITH QUERY EXPANSION";
            }
        }
        return $queryModifiers;
    }

    public function setFirstResult($firstResult = 0)
    {
        $this->firstResult = (int)$firstResult;
        $this->query->setFirstResult($this->firstResult);
    }

    public function addOrderBy($column = null)
    {
        $this->query->addOrderBy($column);
    }

    public function getResults()
    {
        $pages = parent::getResults();
        return $pages;
    }

    public function setFeedTitle($title)
    {
        $this->rssFeedTitle = $title;
    }

    public function getFeedTitle()
    {
        return $this->rssFeedTitle;
    }

    public function setFeedDescription($description)
    {
        $this->rssFeedDescription = $description;
    }

    public function getFeedDescription()
    {
        return $this->rssFeedDescription;
    }

    /**
     * Filters by page theme ID
     * @param array | integer $cParentID
     */
    public function filterByPageThemeID($pageThemeId)
    {
        $db = \Database::get();
        if (is_array($pageThemeId)) {
            $this->query->andWhere(
                $this->query->expr()->in('cv.pThemeID', array_map(array($db, 'quote'), $pageThemeId))
            );
        } else {
            $this->query->andWhere($this->query->expr()->comparison('cv.pThemeID', '=', ':pThemeID'));
            $this->query->setParameter('pThemeID', $pageThemeId, \PDO::PARAM_INT);
        }
    }

    public function filterByPaths($paths, $includeAllChildren = true)
    {
        if (!$includeAllChildren) {
            $db = \Database::get();
            $this->query->andWhere(
                $this->query->expr()->in('pp.cPath', array_map(array($db, 'quote'), $paths))
            );
        } else {
            $clauses = [];
            $parameters = [];
            foreach ($paths as $key => $value) {
                $clauses[] = $this->query->expr()->like('pp.cPath', ':cPath' . $key);
                $parameters[] = $value . '/%';
            }
            $sqlString = "(" . implode(' OR ', $clauses) . ")";
            $this->query->andWhere($sqlString);
            foreach ($parameters as $key => $parameter) {
                $this->query->setParameter('cPath' . $key, $parameter);
            }
        }
        $this->query->andWhere('pp.ppIsCanonical = 1');
    }

    public function filterByPageId($pageId) {
        $db = \Database::get();
        if (is_array($pageId)) {
            $this->query->andWhere(
                $this->query->expr()->in('p.cID', array_map(array($db, 'quote'), $pageId))
            );
        } else {
            $this->query->andWhere($this->query->expr()->comparison('p.cID', '=', ':cID'));
            $this->query->setParameter('p.cID', $pageId, \PDO::PARAM_INT);
        }
    }

    public function filterOutByPageId($pageId) {
        $db = \Database::get();
        if (is_array($pageId)) {
            $this->query->andWhere(
                $this->query->expr()->notIn('p.cID', array_map(array($db, 'quote'), $pageId))
            );
        } else {
            $this->query->andWhere($this->query->expr()->comparison('p.cID', '!=', ':cID'));
            $this->query->setParameter('p.cID', $pageId, \PDO::PARAM_INT);
        }
    }

    public function filterByHasVersionName()
    {
        $this->query->andWhere($this->query->expr()->comparison('cvName', '!=', "''"));
    }

    public function filterByClause($clause)
    {
        $this->query->andWhere($clause);
    }

    public function filterByFulltextKeywords($keywords)
    {
        $this->fullTextKeywords = $keywords;
        $this->isFulltextSearch = true;
        $this->autoSortColumns[] = 'cIndexScore';
        $this->query->where('match(psi.cName, psi.cDescription, psi.content) against (:fulltext2' . $this->getFulltextQueryModifiers() . ')');
        $this->query->setParameter('fulltext2', $this->fullTextKeywords);
    }

    public function filterByAttributes($cObj = false, $attributes = false, $querystringAttributes = [], $defaults = false)
    {
        if (!$cObj) {
            return false;
        } else {
            $this->collectionObject = $cObj;
        }
        if (!$attributes) {
            return false;
        }
        if ($defaults) {
            if (!is_array($defaults)) {
                $defaults = unserialize($defaults);
            }
            foreach ($defaults as $akID => $value) {
                if (empty($value)) {
                    unset($defaults[$akID]);
                } else {
                    $defaults[$akID] = [$value];
                }
            }
        } else {
            $defaults = [];
        }
        if (is_array($querystringAttributes)) {
            $overrideDefaults = [];
            foreach ($querystringAttributes as $querystringAttributeKey => $querystringAttributeValue) {
                if (is_array($querystringAttributeValue)) {
                    foreach ($querystringAttributeValue as $key => $value) {
                        if (empty($value)) {
                            unset($querystringAttributes[$querystringAttributeKey][$key]);
                            if (count($querystringAttributes[$querystringAttributeKey]) == 0) {
                                $overrideDefaults[] = $querystringAttributeKey;
                                unset($querystringAttributes[$querystringAttributeKey]);
                            }
                        }
                    }
                }
            }
        }

        $defaults = $querystringAttributes + $defaults;
        foreach ($overrideDefaults as $key) {
            unset($defaults[$key]);
        }
        $this->querystringAttributes = $defaults;

        foreach ($attributes as $akID => $attributeFilter) {
            $attribute = new StdClass;
            $attribute->keyID = $akID;
            $attribute->filter = $attributeFilter;

            if (is_numeric($akID)) {
                $attributeKey = CollectionAttributeKey::getByID($akID);
                $isStandardProperty = false;
            } elseif (in_array($akID, ['cName', 'cDatePublic', 'cvDatePublic', 'cDateModified', 'cvDateCreated', 'uID'])) {
                $isStandardProperty = true;
            }
            if (is_object($attributeKey) || $isStandardProperty) {
                $dateFormat = 'Y-m-d H:i:s';
                if ($isStandardProperty) {
                    $currentValue = $this->getStandardPropertyAttributeValue($akID);
                    $handle = $this->getStandardPropertyAttributeHandle($akID);
                    $isDate = $this->getStandardPropertyAttributeIsDate($akID);
                } else {
                    $currentValue = $this->collectionObject->getCollectionAttributeValue($attributeKey);
                    $handle = 'ak_' . $attributeKey->getAttributeKeyHandle();
                    if ($attributeKey->getAttributeTypeHandle() == "date_time") {
                        $isDate = true;
                        $dateDisplayMode = $this->db->GetOne('select akDateDisplayMode from atDateTimeSettings where akID = ?', $attributeKey->getAttributeKeyID());
                        $dateDisplayMode = $dateDisplayMode == '' ? 'date_time' : $dateDisplayMode;
                        $dateFormat = $dateDisplayMode == 'date' ? 'Y-m-d' : 'Y-m-d H:i:s';
                    }
                }

                if (!is_object($currentValue)) {
                    trim($currentValue);
                    $currentValue = $this->db->escape($currentValue);
                }

                if (isset($attributeFilter['val1'])) {
                    $attributeFilter['val1'] = $this->getEscapedAttributeFilterValue($attributeFilter['val1'], $isDate, $dateFormat);
                }
                if (isset($attributeFilter['val2'])) {
                    $attributeFilter['val2'] = $this->getEscapedAttributeFilterValue($attributeFilter['val2'], $isDate, $dateFormat);
                }
                if (isset($attributeFilter['val3'])) {
                    $attributeFilter['val3'] = $this->getEscapedAttributeFilterValue($attributeFilter['val3'], $isDate, $dateFormat);
                }

                if ($isDate && array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes)) {
                    if (is_array($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                        foreach ($this->querystringAttributes[$attributeKey->getAttributeKeyID()] as $qsakey => $qsavalue) {
                            trim($this->querystringAttributes[$attributeKey->getAttributeKeyID()][$qsakey]);
                            $this->querystringAttributes[$attributeKey->getAttributeKeyID()][$qsakey] = date($dateFormat, strtotime($this->querystringAttributes[$attributeKey->getAttributeKeyID()][$qsakey]));
                        }
                    } else {
                        trim($this->querystringAttributes[$attributeKey->getAttributeKeyID()]);
                        $this->querystringAttributes[$attributeKey->getAttributeKeyID()] = date($dateFormat, strtotime($this->querystringAttributes[$attributeKey->getAttributeKeyID()]));
                    }
                }

                if ($attributeKey->getAttributeTypeHandle() == "boolean") {
                    $this->runBooleanFilter($attributeKey, $attributeFilter, $currentValue, $handle);
                } elseif ($attributeKey->getAttributeTypeHandle() == "image_file") {
                    $this->runImageFileFilter($attributeKey, $attributeFilter, $currentValue, $handle);
                } elseif ($attributeKey->getAttributeTypeHandle() == "page_selector") {
                    $this->runPageSelectorFilter($attributeKey, $attributeFilter, $currentValue, $handle);
                } elseif ($attributeKey->getAttributeTypeHandle() == "multi_date") {
                    $this->runMultiDateFilter($attributeKey, $attributeFilter, $currentValue, $handle, $attributes);
                } else {
                    $this->runBasicFilter($attributeKey, $attributeFilter, $currentValue, $handle);
                }
            }
        }
    }

    private function getStandardPropertyAttributeValue($akID = false)
    {
        if ($akID == 'uID' || $akID == 'cvAuthorUID') {
            return $this->collectionObject->getCollectionUserID();
        } elseif ($akID == 'cDatePublic' || $akID == 'cvDatePublic') {
            return $this->collectionObject->getCollectionDatePublic();
        } elseif ($akID == 'cDateModified' || $akID == 'cvDateCreated') {
            return $this->collectionObject->getCollectionDateLastModified();
        } elseif ($akID == 'cName' || $akID == 'cvName') {
            return $this->collectionObject->getCollectionName();
        } elseif ($akID == 'cvDescription') {
            return $this->collectionObject->getCollectionDescription();
        } elseif ($akID == 'cID') {
            return $this->collectionObject->getCollectionID();
        } elseif ($akID == 'cvApproverUID') {
            $collectionVersion = CollectionVersion::get($this->collectionObject, 'ACTIVE');
            return $collectionVersion->getVersionApproverUserID();
        }
        return false;
    }

    private function getStandardPropertyAttributeHandle($akID = false)
    {
        if ($akID == 'uID' || $akID == 'cvAuthorUID') {
            return 'p1.uID';
        } elseif ($akID == 'cDatePublic' || $akID == 'cvDatePublic') {
            return 'cvDatePublic';
        } elseif ($akID == 'cDateModified' || $akID == 'cvDateCreated') {
            return 'cDateModified';
        } elseif ($akID == 'cName' || $akID == 'cvName') {
            return 'cvName';
        } elseif ($akID == 'cvDescription') {
            return 'cvDescription';
        } elseif ($akID == 'cvApproverUID') {
            return 'cvApproverUID';
        } elseif ($akID == 'cID') {
            return 'p.cID';
        }
        return false;
    }

    private function getStandardPropertyAttributeIsDate($akID = false)
    {
        if ($akID == 'cDatePublic' || $akID == 'cvDatePublic' || $akID == 'cDateModified' || $akID == 'cvDateCreated') {
            return true;
        }
        return false;
    }

    private function getEscapedAttributeFilterValue($val, $isDate, $dateFormat)
    {
        if (is_array($val)) {
            foreach ($val as $k => $v) {
                trim($v);
                if ($isDate && $v) {
                    $v = date($dateFormat, strtotime($v));
                }
                $val[$k] = $v;
                //$val[$k] = $this->db->escape($v);
            }
            return $val;
        }
        trim($val);
        if ($isDate && $val) {
            $val = date($dateFormat, strtotime($val));
        }
        return $this->db->escape($val);
    }

    private function runBooleanFilter($attributeKey, $attributeFilter, $currentValue, $handle)
    {
        if (strpos($attributeFilter['eval'], 'attribute') !== false) {
            if ($attributeFilter['eval'] == 'equals_attribute') {
                $this->query->andWhere("(" . $handle . "=ak_" . $attributeFilter['val1'] . ")");
            } elseif ($attributeFilter['eval'] == 'not_equals_attribute') {
                $this->query->andWhere("(" . $handle . "!=ak_" . $attributeFilter['val1'] . ")");
            } elseif ($attributeFilter['eval'] == 'less_than_attribute') {
                $this->query->andWhere("(" . $handle . "<ak_" . $attributeFilter['val1'] . ")");
            } elseif ($attributeFilter['eval'] == 'less_than_or_equals_attribute') {
                $this->query->andWhere("(" . $handle . "<=ak_" . $attributeFilter['val1'] . ")");
            } elseif ($attributeFilter['eval'] == 'greater_than_attribute') {
                $this->query->andWhere("(" . $handle . ">ak_" . $attributeFilter['val1'] . ")");
            } elseif ($attributeFilter['eval'] == 'greater_than_or_equals_attribute') {
                $this->query->andWhere("(" . $handle . ">=ak_" . $attributeFilter['val1'] . ")");
            }
        } elseif ($attributeFilter['eval'] == "matches_all") {
            $this->filterByAttribute($attributeKey->getAttributeKeyHandle(), $currentValue ? 1 : 0);
        } elseif ($attributeFilter['eval'] == "querystring_all") {
            if (array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes)) {
                if (isset($this->querystringAttributes[$attributeKey->getAttributeKeyID()]) && !empty($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                    $matchVal = 1;
                    if (!$this->querystringAttributes[$attributeKey->getAttributeKeyID()] || $this->querystringAttributes[$attributeKey->getAttributeKeyID()] == 'false') {
                        $matchVal = 0;
                    }
                    $this->filterByAttribute($attributeKey->getAttributeKeyHandle(), $matchVal);
                }
            }
        } elseif ($attributeFilter['eval'] == "not_matches_all") {
            $this->filterByAttribute($attributeKey->getAttributeKeyHandle(), $currentValue ? 0 : 1);
        } elseif ($attributeFilter['eval'] == "not_querystring_all") {
            if (array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes)) {
                if (isset($this->querystringAttributes[$attributeKey->getAttributeKeyID()]) && !empty($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                    $matchVal = 0;
                    if (!$this->querystringAttributes[$attributeKey->getAttributeKeyID()] || $this->querystringAttributes[$attributeKey->getAttributeKeyID()] == 'false') {
                        $matchVal = 1;
                    }
                    $this->filterByAttribute($attributeKey->getAttributeKeyHandle(), $matchVal);
                }
            }
        } else {
            $this->filterByAttribute($attributeKey->getAttributeKeyHandle(), $attributeFilter['eval'] == 'true' ? 1 : 0);
        }
    }

    private function runImageFileFilter($attributeKey, $attributeFilter, $currentValue, $handle)
    {
        if ($attributeFilter['eval'] == 'not_empty') {
            $this->query->andWhere("/* image/file */ (" . $handle . "!='' AND " . $handle . "!=0)");
        } elseif ($attributeFilter['eval'] == 'is_empty') {
            $this->query->andWhere("/* image/file */ (" . $handle . "='' OR  " . $handle . "=0)");
        } elseif ($attributeFilter['eval'] == 'id_is_exactly') {
            $this->query->andWhere("/* image/file */ (fID='" . $attributeFilter['val1'] . "')");
        } elseif ($attributeFilter['eval'] == 'id_is_not_exactly') {
            $this->query->andWhere("/* image/file */ (fID!='" . $attributeFilter['val1'] . "')");
        } else {
            // more intensive db stuff
            $fl = new FileList();
            if ($attributeFilter['eval'] == 'contains') {
                $fl->query->andWhere("/* image/file */ (fvFilename LIKE '%" . $attributeFilter['val1'] . "%')");
            } elseif ($attributeFilter['eval'] == 'not_contains') {
                // do a negative search, it should return less files
                $fl->query->andWhere("/* image/file */ (fvFilename LIKE '%" . $attributeFilter['val1'] . "%')");
            } elseif ($attributeFilter['eval'] == 'is_exactly') {
                $fl->query->andWhere("/* image/file */ (fvFilename LIKE '" . $attributeFilter['val1'] . "')");
            } elseif ($attributeFilter['eval'] == 'starts_with') {
                $fl->query->andWhere("/* image/file */ (fvFilename LIKE '" . $attributeFilter['val1'] . "%')");
            } elseif ($attributeFilter['eval'] == 'ends_with') {
                $fl->query->andWhere("/* image/file */ (fvFilename LIKE '%" . $attributeFilter['val1'] . "')");
            } elseif ($attributeFilter['eval'] == 'matches_all') {
                $fl->query->andWhere("/* image/file */ (fvFilename LIKE '" . $attributeFilter['val1'] . "')");
            } elseif ($attributeFilter['eval'] == 'not_matches_all') {
                // do a negative search, it should return less files
                $fl->query->andWhere("/* image/file */ (fvFilename LIKE '" . $attributeFilter['val1'] . "')");
            }
            $fl->setItemsPerPage(1000000);
            $files = $fl->get();
            $filter_file_ids = [];
            foreach ($files as $file) {
                $filter_file_ids[] = $file->getFileID();
            }
            if (count($filter_file_ids)) {
                if ($attributeFilter['eval'] == 'not_matches_all' || $attributeFilter['eval'] == 'not_contains') {
                    $this->query->andWhere("/* image/file */ (" . $handle . " NOT IN (" . implode(',', $filter_file_ids) . "))");
                } else {
                    $this->query->andWhere("/* image/file */ (" . $handle . " IN (" . implode(',', $filter_file_ids) . "))");
                }
            } else {
                if ($attributeFilter['eval'] == 'not_matches_all' || $attributeFilter['eval'] == 'not_contains') {
                    $this->query->andWhere("/* image/file */ (" . $handle . "!='' AND " . $handle . "!=0)");
                } else {
                    $this->query->andWhere("/* image/file */ (1!=1)");
                }
            }
        }
    }

    private function runPageSelectorFilter($attributeKey, $attributeFilter, $currentValue, $handle)
    {
        if ($attributeFilter['eval'] == 'not_empty') {
            $this->query->andWhere("(" . $handle . "!='' AND " . $handle . "!=0)");
        } elseif ($attributeFilter['eval'] == 'is_empty') {
            $this->query->andWhere("(" . $handle . "='' OR  " . $handle . "=0)");
        } elseif ($attributeFilter['eval'] == 'id_is_exactly') {
            $this->query->andWhere("(p1.cID='" . $attributeFilter['val1'] . "')");
        } elseif ($attributeFilter['eval'] == 'id_is_not_exactly') {
            $this->query->andWhere("(p1.cID!='" . $attributeFilter['val1'] . "')");
        } elseif ($attributeFilter['eval'] == 'id_matches') {
            $this->query->andWhere("(" . $handle . "='" . $this->cID . "')");
        } elseif ($attributeFilter['eval'] == 'id_not_matches') {
            $this->query->andWhere("(" . $handle . "!='" . $this->cID . "')");
        } elseif ($attributeFilter['eval'] == 'matches_all') {
            $this->query->andWhere("(" . $handle . "='" . $currentValue . "')");
        } elseif ($attributeFilter['eval'] == 'not_matches_all') {
            $this->query->andWhere("(" . $handle . "!='" . $currentValue . "')");
        } else {
            // more intensive db stuff
            $pl2 = new PageList();
            if ($attributeFilter['eval'] == 'contains') {
                $pl2->query->andWhere("cvName LIKE '%" . $attributeFilter['val1'] . "%'");
            } elseif ($attributeFilter['eval'] == 'not_contains') {
                // do a negative search, it should return less pages
                $pl2->query->andWhere("cvName LIKE '%" . $attributeFilter['val1'] . "%'");
            } elseif ($attributeFilter['eval'] == 'is_exactly') {
                $pl2->query->andWhere("cvName LIKE '" . $attributeFilter['val1'] . "'");
            } elseif ($attributeFilter['eval'] == 'starts_with') {
                $pl2->query->andWhere("cvName LIKE '" . $attributeFilter['val1'] . "%'");
            } elseif ($attributeFilter['eval'] == 'ends_with') {
                $pl2->query->andWhere("cvName LIKE '%" . $attributeFilter['val1'] . "'");
            } elseif ($attributeFilter['eval'] == "querystring_all") {
                if (array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes)) {
                    if (isset($this->querystringAttributes[$attributeKey->getAttributeKeyID()]) && !empty($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                        foreach ($this->querystringAttributes[$attributeKey->getAttributeKeyID()] as $attributeKeyElement) {
                            $pl2->query->andWhere("cvName LIKE '%" . $this->db->escape($attributeKeyElement) . "%'");
                        }
                    }
                }
            } elseif ($attributeFilter['eval'] == "not_querystring_all") {
                if (array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes)) {
                    if (isset($this->querystringAttributes[$attributeKey->getAttributeKeyID()]) && !empty($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                        foreach ($this->querystringAttributes[$attributeKey->getAttributeKeyID()] as $attributeKeyElement) {
                            $pl2->query->andWhere("cvName NOT LIKE '%" . $this->db->escape($attributeKeyElement) . "%'");
                        }
                    }
                }
            }
            $pl2->setItemsPerPage(1000000);
            $filterPages = $pl2->get();
            $filter_page_ids = [];
            foreach ($filterPages as $filterPage) {
                $filter_page_ids[] = $filterPage->getCollectionID();
            }
            if (count($filter_page_ids)) {
                if ($attributeFilter['eval'] == 'not_matches_all' || $attributeFilter['eval'] == 'not_querystring_all' || $attributeFilter['eval'] == 'not_contains') {
                    $this->query->andWhere("(p1.cID NOT IN (" . implode(',', $filter_page_ids) . "))");
                } else {
                    $this->query->andWhere("(p1.cID IN (" . implode(',', $filter_page_ids) . "))");
                }
            } else {
                if ($attributeFilter['eval'] == 'not_matches_all' || $attributeFilter['eval'] == 'not_querystring_all' || $attributeFilter['eval'] == 'not_contains') {
                    $this->query->andWhere("(" . $handle . "!='' AND " . $handle . "!=0)");
                } else {
                    $this->query->andWhere("(1!=1)");
                }
            }
        }
    }

    private function runMultiDateFilter($attributeKey, $attributeFilter, $currentValue, $handle)
    {
        if ($attributeFilter['eval'] == 'not_empty') {
            $this->query->andWhere("(" . $handle . "!='' AND " . $handle . " IS NOT NULL)");
        } elseif ($attributeFilter['eval'] == 'is_empty') {
            $this->query->andWhere("(" . $handle . "='' OR " . $handle . " IS NULL)");
        } elseif ($attributeFilter['eval'] == 'equals') {
            $test_date = date('Y/m/d', strtotime($attributeFilter['eval']));
            $this->query->andWhere("(" . $handle . " REGEXP '" . $test_date . "')");
        } elseif ($attributeFilter['eval'] == 'not_equals') {
            $test_date = date('Y/m/d', strtotime($attributeFilter['eval']));
            $this->query->andWhere("(" . $handle . " NOT REGEXP '" . $test_date . "')");
        } elseif (in_array($attributeFilter['eval'], ['yesterday', 'today', 'tomorrow'])) {
            $difs = ['yesterday' => '-1 day', 'today' => '+0 days', 'tomorrow' => '+1 day'];
            $test_date = date('Y/m/d', strtotime($difs[$attributeFilter['eval']]));
            $this->query->andWhere("(" . $handle . " REGEXP '" . $test_date . "')");
        } elseif ($attributeFilter['eval'] == 'querystring_all' || $attributeFilter['eval'] == 'querystring_any') {
            if (array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes)) {
                if (isset($this->querystringAttributes[$attributeKey->getAttributeKeyID()]) && !empty($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                    if (!is_array($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                        $this->querystringAttributes[$attributeKey->getAttributeKeyID()] = [$this->querystringAttributes[$attributeKey->getAttributeKeyID()]];
                    }
                    $clauseParts = [];
                    foreach ($this->querystringAttributes[$attributeKey->getAttributeKeyID()] as $attributeKeyElement) {
                        $test_date = date('Y/m/d', strtotime($attributeKeyElement));
                        $clausePart = "(" . $handle . " REGEXP '" . $test_date . "')";
                        $clauseParts[] = $clausePart;
                    }
                    $concat = $attributeFilter['eval'] == 'querystring_all' ? ' AND ' : ' OR ';
                    $this->query->andWhere("(" . implode($concat, $clauseParts) . ")");
                }
            }
        } elseif ($attributeFilter['eval'] == 'not_querystring_all') {
            if (array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes)) {
                if (isset($this->querystringAttributes[$attributeKey->getAttributeKeyID()]) && !empty($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                    if (!is_array($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                        $this->querystringAttributes[$attributeKey->getAttributeKeyID()] = [$this->querystringAttributes[$attributeKey->getAttributeKeyID()]];
                    }
                    $clauseParts = [];
                    foreach ($this->querystringAttributes[$attributeKey->getAttributeKeyID()] as $attributeKeyElement) {
                        $test_date = date('Y/m/d', strtotime($attributeKeyElement));
                        $clausePart = "(" . $handle . " NOT REGEXP '" . $test_date . "')";
                        $clauseParts[] = $clausePart;
                    }
                    $this->query->andWhere("(" . implode(' AND ', $clauseParts) . ")");
                }
            }
        }
    }

    private function runBasicFilter($attributeKey, $attributeFilter, $currentValue, $handle)
    {
        if (in_array($attributeFilter['eval'], ['past', 'future', 'not_future', 'today_and_future', 'yesterday', 'today', 'tomorrow'])) {
            $this->runDateFilter($attributeKey, $attributeFilter, $currentValue, $handle);
        } else {
            if (!is_array($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                $this->querystringAttributes[$attributeKey->getAttributeKeyID()] = [$this->querystringAttributes[$attributeKey->getAttributeKeyID()]];
            }
            if (strpos($attributeFilter['eval'], 'attribute') !== false) {
                if ($attributeFilter['eval'] == 'equals_attribute') {
                    $this->query->andWhere("(" . $handle . "=ak_" . $attributeFilter['val1'] . ")");
                } elseif ($attributeFilter['eval'] == 'not_equals_attribute') {
                    $this->query->andWhere("(" . $handle . "!=ak_" . $attributeFilter['val1'] . ")");
                } elseif ($attributeFilter['eval'] == 'less_than_attribute') {
                    $this->query->andWhere("(" . $handle . "<ak_" . $attributeFilter['val1'] . ")");
                } elseif ($attributeFilter['eval'] == 'less_than_or_equals_attribute') {
                    $this->query->andWhere("(" . $handle . "<=ak_" . $attributeFilter['val1'] . ")");
                } elseif ($attributeFilter['eval'] == 'greater_than_attribute') {
                    $this->query->andWhere("(" . $handle . ">ak_" . $attributeFilter['val1'] . ")");
                } elseif ($attributeFilter['eval'] == 'greater_than_or_equals_attribute') {
                    $this->query->andWhere("(" . $handle . ">=ak_" . $attributeFilter['val1'] . ")");
                }
            } elseif ($attributeFilter['eval'] == 'not_empty') {
                $this->query->andWhere("(" . $handle . "!='' AND " . $handle . " IS NOT NULL)");
            } elseif ($attributeFilter['eval'] == 'is_empty') {
                $this->query->andWhere("(" . $handle . "='' OR " . $handle . " IS NULL)");
            } elseif ($attributeFilter['eval'] == 'equals') {
                $this->query->andWhere("(" . $handle . "='" . $attributeFilter['val1'] . "' OR " . $handle . " LIKE '%\\n" . $attributeFilter['val1'] . "\\n%')");
            } elseif ($attributeFilter['eval'] == 'not_equals') {
                $this->query->andWhere("(" . $handle . "!='" . $attributeFilter['val1'] . "' AND " . $handle . " NOT LIKE '%\\n" . $attributeFilter['val1'] . "\\n%')");
            } elseif ($attributeFilter['eval'] == 'contains') {
                $clause = $handle . " LIKE '%" . $attributeFilter['val1'] . "%'";
                if (is_object($attributeKey)) {
                    if ($handle == 'ak_meta_title')
                        $clause .= " OR cvName LIKE '%" . $attributeFilter['val1'] . "%'";
                    if ($handle == 'ak_meta_description')
                        $clause .= " OR cvDescription LIKE '%" . $attributeFilter['val1'] . "%'";
                }
                $this->query->andWhere("(" . $clause . ")");
            } elseif ($attributeFilter['eval'] == 'not_contains') {
                $clause = $handle . " NOT LIKE '%" . $attributeFilter['val1'] . "%'";
                if (is_object($attributeKey)) {
                    if ($handle == 'ak_meta_title')
                        $clause .= " AND cvName NOT LIKE '%" . $attributeFilter['val1'] . "%'";
                    if ($handle == 'ak_meta_description')
                        $clause .= " AND cvDescription NOT LIKE '%" . $attributeFilter['val1'] . "%'";
                }
                $this->query->andWhere("(" . $clause . ")");
            } elseif ($attributeFilter['eval'] == 'is_exactly') {
                $clause = $handle . " LIKE '" . $attributeFilter['val1'] . "'";
                if (is_object($attributeKey)) {
                    if ($handle == 'ak_meta_title')
                        $clause .= " OR cvName LIKE '" . $attributeFilter['val1'] . "'";
                    if ($handle == 'ak_meta_description')
                        $clause .= " OR cvDescription LIKE '" . $attributeFilter['val1'] . "'";
                }
                $this->query->andWhere("(" . $clause . ")");
            } elseif ($attributeFilter['eval'] == 'matches_all' || $attributeFilter['eval'] == 'matches_any') {
                if ($attributeKey->getAttributeTypeHandle() == "select") {
                    $clause = [];
                    if (is_object($currentValue)) {
                        foreach ($currentValue->getOptions() as $val) {
                            $clause[] = "(" . $handle . " LIKE '%\\n" . $this->db->escape($val->value) . "\\n%')";
                        }
                        if (count($clause)) {
                            if ($attributeFilter['eval'] == 'matches_all') {
                                $this->query->andWhere("(" . implode(' AND ', $clause) . ")");
                            } else {
                                $this->query->andWhere("((" . implode(' OR ', $clause) . ") AND (" . $handle . "!='' AND " . $handle . " IS NOT NULL))");
                            }
                        }
                    } else {
                        if ($attributeFilter['eval'] == 'matches_all') {
                            $this->query->andWhere("(" . $handle . "!='' AND " . $handle . " IS NOT NULL)");
                        }
                    }
                } else {
                    $clause = $handle . " LIKE '" . $currentValue . "'";
                    if (is_object($attributeKey)) {
                        if ($handle == 'ak_meta_title')
                            $clause .= " OR cvName LIKE '" . $currentValue . "'";
                        if ($handle == 'ak_meta_description')
                            $clause .= " OR cvDescription LIKE '" . $currentValue . "'";
                    }
                    $this->query->andWhere("(" . $clause . ")");
                }
            } elseif ($attributeFilter['eval'] == 'querystring_all' || $attributeFilter['eval'] == 'querystring_any') {
                if (array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes)) {
                    if (isset($this->querystringAttributes[$attributeKey->getAttributeKeyID()]) && !empty($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                        if (!is_array($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                            $this->querystringAttributes[$attributeKey->getAttributeKeyID()] = [$this->querystringAttributes[$attributeKey->getAttributeKeyID()]];
                        }
                        $startWildcard = ($isDate && $dateDisplayMode == 'date') ? '' : '%';
                        $clauseParts = [];
                        if (count($this->querystringAttributes[$attributeKey->getAttributeKeyID()]) > 0) {
                            foreach ($this->querystringAttributes[$attributeKey->getAttributeKeyID()] as $attributeKeyElement) {
                                $attributeKeyElement = trim($attributeKeyElement);
                                $clausePart = false;
                                if ($attributeKeyElement) {
                                    if ($attributeKey->getAttributeTypeHandle() == "select") {
                                        $clausePart = "(" . $handle . " LIKE '%\\n" . $this->db->escape($attributeKeyElement) . "\\n%')";
                                    } else {
                                        $clausePart = "(";
                                        $clausePart .= $handle . " LIKE '" . $startWildcard . $this->db->escape($attributeKeyElement) . "%'";
                                        if (is_object($attributeKey)) {
                                            if ($handle == 'ak_meta_title')
                                                $clausePart .= " OR cvName LIKE '%" . $attributeKeyElement . "%'";
                                            if ($handle == 'ak_meta_description')
                                                $clausePart .= " OR cvDescription LIKE '%" . $attributeKeyElement . "%'";
                                        }
                                        $clausePart .= ")";
                                    }
                                }
                                if ($clausePart) {
                                    $clauseParts[] = $clausePart;
                                }
                            }
                            if (count($clauseParts) > 0) {
                                $concat = $attributeFilter['eval'] == 'querystring_all' ? ' AND ' : ' OR ';
                                $this->query->andWhere("(" . implode($concat, $clauseParts) . ")");
                            }
                        }
                    }
                }
            } elseif ($attributeFilter['eval'] == 'not_matches_all') {
                if ($attributeKey->getAttributeTypeHandle() == "select") {
                    $clause = [];
                    foreach ($currentValue->getOptions() as $val) {
                        $clause[] = "(" . $handle . " NOT LIKE '%\\n" . $val->value . "\\n%')";
                    }
                    if (count($clause)) {
                        $this->query->andWhere("(" . implode(' AND ', $clause) . ")");
                    }
                } else {
                    $clause = $handle . " NOT LIKE '" . $currentValue . "'";
                    if (is_object($attributeKey)) {
                        if ($handle == 'ak_meta_title')
                            $clause .= " AND cvName NOT LIKE '" . $currentValue . "'";
                        if ($handle == 'ak_meta_description')
                            $clause .= " AND cvDescription NOT LIKE '" . $currentValue . "'";
                    }
                    $this->query->andWhere("(" . $clause . ")");
                }
            } elseif ($attributeFilter['eval'] == 'not_querystring_all') {
                if (array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes)) {
                    if (isset($this->querystringAttributes[$attributeKey->getAttributeKeyID()]) && !empty($this->querystringAttributes[$attributeKey->getAttributeKeyID()])) {
                        if ($attributeKey->getAttributeTypeHandle() == "select") {
                            $clauseParts = [];
                            foreach ($this->querystringAttributes[$attributeKey->getAttributeKeyID()] as $attributeKeyElement) {
                                $clauseParts[] = "(" . $handle . " NOT LIKE '%\\n" . $this->db->escape($attributeKeyElement) . "\\n%')";
                            }
                            $this->query->andWhere("(" . implode(' AND ', $clauseParts) . ")");
                        } else {
                            $attributeKeyElement = $this->db->escape($this->querystringAttributes[$attributeKey->getAttributeKeyID()][0]);
                            $clause = $handle . " NOT LIKE '%" . $attributeKeyElement . "%'";
                            if (is_object($attributeKey)) {
                                if ($handle == 'ak_meta_title')
                                    $clause .= " OR cvName LIKE '%" . $attributeKeyElement . "%'";
                                if ($handle == 'ak_meta_description')
                                    $clause .= " OR cvDescription LIKE '%" . $attributeKeyElement . "%'";
                            }
                            $this->query->andWhere("(" . $clause . ")");
                        }
                    }
                }
            } elseif ($attributeFilter['eval'] == 'starts_with') {
                $clause = $handle . " LIKE '" . $attributeFilter['val1'] . "%'";
                if (is_object($attributeKey)) {
                    if ($handle == 'ak_meta_title')
                        $clause .= " OR cvName LIKE '" . $attributeFilter['val1'] . "%'";
                    if ($handle == 'ak_meta_description')
                        $clause .= " OR cvDescription LIKE '" . $attributeFilter['val1'] . "%'";
                }
                $this->query->andWhere("(" . $clause . ")");
            } elseif ($attributeFilter['eval'] == 'ends_with') {
                $clause = $handle . " LIKE '%" . $attributeFilter['val1'] . "'";
                if (is_object($attributeKey)) {
                    if ($handle == '_ak_meta_title')
                        $clause .= " OR cvName LIKE '%" . $attributeFilter['val1'] . "'";
                    if ($handle == 'ak_meta_description')
                        $clause .= " OR cvDescription LIKE '%" . $attributeFilter['val1'] . "'";
                }
                $this->query->andWhere("(" . $clause . ")");
            } elseif ($attributeFilter['eval'] == 'less_than') {
                $this->query->andWhere("(" . $handle . "<'" . $attributeFilter['val1'] . "')");
            } elseif ($attributeFilter['eval'] == 'less_than_match') {
                $this->query->andWhere("(" . $handle . "<'" . $currentValue . "')");
            } elseif ($attributeFilter['eval'] == 'less_than_querystring') {
                if ($isStandardProperty)
                    $this->query->andWhere("(" . $handle . "<'" . $currentValue . "')");
                elseif (array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes) && isset($this->querystringAttributes[$attributeKey->getAttributeKeyID()][0]) && !empty($this->querystringAttributes[$attributeKey->getAttributeKeyID()][0])) {
                    $this->query->andWhere("(" . $handle . "<'" . $this->querystringAttributes[$attributeKey->getAttributeKeyID()][0] . "')");
                }
            } elseif ($attributeFilter['eval'] == 'less_than_or_equal_to') {
                $this->query->andWhere("(" . $handle . "<='" . $attributeFilter['val1'] . "')");
            } elseif ($attributeFilter['eval'] == 'less_than_or_equal_to_match') {
                $this->query->andWhere("(" . $handle . "<='" . $currentValue . "')");
            } elseif ($attributeFilter['eval'] == 'less_than_or_equal_to_querystring') {
                if ($isStandardProperty)
                    $this->query->andWhere("(" . $handle . "<='" . $currentValue . "')");
                elseif (array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes) && isset($this->querystringAttributes[$attributeKey->getAttributeKeyID()][0]) && !empty($this->querystringAttributes[$attributeKey->getAttributeKeyID()][0])) {
                    $this->query->andWhere("(" . $handle . "<='" . $this->querystringAttributes[$attributeKey->getAttributeKeyID()][0] . "')");
                }
            } elseif ($attributeFilter['eval'] == 'more_than') {
                $this->query->andWhere("(" . $handle . ">'" . $attributeFilter['val1'] . "')");
            } elseif ($attributeFilter['eval'] == 'more_than_match') {
                $this->query->andWhere("(" . $handle . ">'" . $currentValue . "')");
            } elseif ($attributeFilter['eval'] == 'more_than_querystring') {
                if ($isStandardProperty)
                    $this->query->andWhere("(" . $handle . ">'" . $currentValue . "')");
                elseif (array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes) && isset($this->querystringAttributes[$attributeKey->getAttributeKeyID()][0]) && !empty($this->querystringAttributes[$attributeKey->getAttributeKeyID()][0])) {
                    $this->query->andWhere("(" . $handle . ">'" . $this->querystringAttributes[$attributeKey->getAttributeKeyID()][0] . "')");
                }
            } elseif ($attributeFilter['eval'] == 'more_than_or_equal_to') {
                $this->query->andWhere("(" . $handle . ">='" . $attributeFilter['val1'] . "')");
            } elseif ($attributeFilter['eval'] == 'more_than_or_equal_to_match') {
                $this->query->andWhere("(" . $handle . ">='" . $currentValue . "')");
            } elseif ($attributeFilter['eval'] == 'more_than_or_equal_to_querystring') {
                if ($isStandardProperty)
                    $this->query->andWhere("(" . $handle . ">='" . $currentValue . "')");
                elseif (array_key_exists($attributeKey->getAttributeKeyID(), $this->querystringAttributes) && isset($this->querystringAttributes[$attributeKey->getAttributeKeyID()][0]) && !empty($this->querystringAttributes[$attributeKey->getAttributeKeyID()][0])) {
                    $this->query->andWhere("(" . $handle . ">='" . $this->querystringAttributes[$attributeKey->getAttributeKeyID()][0] . "')");
                }
            } elseif ($attributeFilter['eval'] == 'between_inclusive') {
                $this->query->andWhere("(" . $handle . ">='" . $attributeFilter['val1'] . "' AND " . $handle . "<='" . $attributeFilter['val2'] . "')");
            } elseif ($attributeFilter['eval'] == 'between_exclusive') {
                $this->query->andWhere("(" . $handle . ">'" . $attributeFilter['val1'] . "' AND " . $handle . "<'" . $attributeFilter['val2'] . "')");
            } elseif ($attributeFilter['eval'] == 'not_between_inclusive') {
                $this->query->andWhere("(" . $handle . "<='" . $attributeFilter['val1'] . "' OR " . $handle . ">='" . $attributeFilter['val2'] . "')");
            } elseif ($attributeFilter['eval'] == 'not_between_exclusive') {
                $this->query->andWhere("(" . $handle . "<'" . $attributeFilter['val1'] . "' OR " . $handle . ">'" . $attributeFilter['val2'] . "')");
            }
        }
    }

    private function runDateFilter($attributeKey, $attributeFilter, $currentValue, $handle)
    {
        if ($attributeFilter['eval'] == 'past') {
            $test_date = date('Y-m-d') . " 00:00:00";
            $this->query->andWhere("(" . $handle . "<'" . $test_date . "')");
        } elseif ($attributeFilter['eval'] == 'future') {
            $test_date = date('Y-m-d', strtotime('+1 day')) . " 00:00:00";
            $this->query->andWhere("(" . $handle . ">='" . $test_date . "')");
        } elseif ($attributeFilter['eval'] == 'not_future') {
            $test_date = date('Y-m-d') . " 23:59:59";
            $this->query->andWhere("(" . $handle . "<='" . $test_date . "')");
        } elseif ($attributeFilter['eval'] == 'today_and_future') {
            $test_date = date('Y-m-d') . " 00:00:00";
            $this->query->andWhere("(" . $handle . ">='" . $test_date . "')");
        } elseif (in_array($attributeFilter['eval'], ['yesterday', 'today', 'tomorrow'])) {
            $difs = ['yesterday' => '-1 day', 'today' => '+0 days', 'tomorrow' => '+1 day'];
            $test_date = date('Y-m-d', strtotime($difs[$attributeFilter['eval']]));
            $this->query->andWhere("(" . $handle . ">='" . $test_date . " 00:00:00' AND " . $handle . "<='" . $test_date . " 23:59:59')");
        }
    }

}
