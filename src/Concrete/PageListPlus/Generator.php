<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\PageListPlus;

use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;
use Concrete\Core\Page\Page;
use Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Concrete\Core\Page\Template as PageTemplate;
use Concrete\Core\Block\BlockType\BlockType as BlockType;
use Database;
use Concrete\Core\Http\Request;
use Concrete\Core\Utility\Service\Text as TextHelper;
use Concrete\Core\Page\Collection\Version\Version as CollectionVersion;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Filter;

defined('C5_EXECUTE') or die("Access Denied.");

class Generator
{
    public $pageListPlus;
    private $settings = [];
    private $currentPage = 1;
    private $isFulltextSearch = false;
    private $searchValues = [];
    private $isPreview = false;

    public function __construct($settings = [])
    {
        $this->pageListPlus = new PageListPlus();
        if (!is_array($this->settings['pageAttributeId'])) {
            $this->settings['pageAttributeId'] = [];
        }
        if (!is_array($this->settings['pageAttributeIdsUsedInSearch'])) {
            $this->settings['pageAttributeIdsUsedInSearch'] = [];
        }
        if (isset($settings['previewUrl'])) {
            $this->isPreview = true;
        }

        $this->settings = $settings;
    }

    public function generate()
    {
        $this->setDebug();
        $this->setCurrentPage();

        // Page Selection Tab
        $this->filterByPageTypeIds();
        $this->filterByPageTemplateIds();
        $this->filterByPageThemeIds();
        $this->filterByPageLocation();
        $this->setIgnorePermissions();
        $this->setDisplayAliases();

        // Search Tab
        $this->setSearchCriteria();

        // Filter Tab
        $this->filterByQuery();
        $this->filterByKeywords();
        $this->filterByKeywordsRelatedToArea();
        $this->filterOutCurrentPage();
        $this->setCustomAttributeFilter();

        // Sort Tab
        $this->setupSorting();

        // Display Tab
        $this->setItemsPerPage();
        $this->skipTopResults();
        $this->setupRss();
        return $this->pageListPlus;
    }

    private function setDebug()
    {
        if (!isset($this->settings['showDebugInformation']) || !$this->settings['showDebugInformation']) {
            return;
        }
        $this->pageListPlus->debug();
    }

    private function setCurrentPage()
    {
        $currentPageId = $this->settings['current_page'] ? $this->settings['current_page'] : null;
        if (!$currentPageId) {
            $page = Page::getCurrentPage();
            if (isset($page->cID)) {
                $currentPageId = $page->cID;
            }
        }
        if (!$currentPageId) {
            $currentPageId = 1;
        }
        $this->currentPage = Page::getByID($currentPageId);
        $this->settings['current_page'] = $currentPageId;
    }

    private function filterByPageTypeIds()
    {
        if (!isset($this->settings['pageTypeId'])) {
            return;
        }
        $pageTypeIds = $this->settings['pageTypeId'];
        if ($pageTypeIds && count($pageTypeIds) > 0) {
            if (!is_array($pageTypeIds)) {
                $pageTypeIds = [$pageTypeIds];
            }
            $filterPageTypeIds = [];
            foreach ($pageTypeIds as $pageTypeId) {
                if ($pageTypeId > 0) {
                    $filterPageTypeIds[] = $pageTypeId;
                }
            }
            if (count($filterPageTypeIds) > 0) {
                $this->pageListPlus->filterByPageTypeID($filterPageTypeIds);
            }
        }
    }

    private function filterByPageTemplateIds()
    {
        if (!isset($this->settings['pageTemplateId'])) {
            return;
        }
        $pageTemplateIds = $this->settings['pageTemplateId'];
        if ($pageTemplateIds && count($pageTemplateIds) > 0) {
            if (!is_array($pageTemplateIds)) {
                $pageTemplateIds = [$pageTemplateIds];
            }
            foreach ($pageTemplateIds as $pageTemplateId) {
                if ($pageTemplateId > 0) {
                    $this->pageListPlus->filterByPageTemplate(PageTemplate::getByID($pageTemplateId));
                }
            }
        }
    }

    private function filterByPageThemeIds()
    {
        if (!isset($this->settings['pageThemeId'])) {
            return;
        }
        $pageThemeIds = $this->settings['pageThemeId'];
        if ($pageThemeIds && count($pageThemeIds) > 0) {
            if (!is_array($pageThemeIds)) {
                $pageThemeIds = [$pageThemeIds];
            }
            $filterPageThemeIds = [];
            foreach ($pageThemeIds as $pageThemeId) {
                if ($pageThemeId > 0) {
                    $filterPageThemeIds[] = $pageThemeId;
                }
            }
            if (count($filterPageThemeIds) > 0) {
                $this->pageListPlus->filterByPageThemeID($filterPageThemeIds);
            }
        }
    }

    private function filterByPageLocation()
    {
        if (!isset($this->settings['parentPageId'])) {
            return;
        }
        $parentPageId = $this->settings['parentPageId'];
        if ($parentPageId == 'EVERYWHERE') {
            return;
        }
        if ($parentPageId == 'CURRENT_BRANCH') {
            $currentPageId = (isset($this->settings['current_page']) && !is_null($this->settings['current_page'])) ? $this->settings['current_page'] : 0;
            if ($currentPageId > 1) {
                $parentPageId = $currentPageId;
                while ($parentPageId > 1) {
                    $branchPageId = $parentPageId;
                    $parentPageId = Page::getByID($branchPageId)->getCollectionParentID();
                }
                $parentPageId = $branchPageId;
            } else {
                $parentPageId = 1;
            }
        } elseif (in_array($parentPageId, ['CURRENT_LEVEL', 'PARENT_LEVEL'])) {
            $currentPageId = (isset($this->settings['current_page']) && !is_null($this->settings['current_page'])) ? $this->settings['current_page'] : 0;
            if ($currentPageId > 1) {
                $parentPageId = Page::getByID($currentPageId)->getCollectionParentID();
                if ($parentPageId == 'PARENT_LEVEL') {
                    if ($parentPageId > 1) {
                        $parentPageId = Page::getByID($parentPageId)->getCollectionParentID();
                    } else {
                        $parentPageId = 1;
                    }
                }
            } else {
                $parentPageId = 1;
            }
        } elseif ($parentPageId == 'OTHER') {
            $parentPageId = isset($this->settings['parentPageIdValue']) ? $this->settings['parentPageIdValue'] : 1;
        } elseif ($parentPageId == 'BELOW_HERE') {
            $parentPageId = (isset($this->settings['current_page']) && !is_null($this->settings['current_page'])) ? $this->settings['current_page'] : 1;
        }
        if (isset($this->settings['includeAllDescendents']) && $this->settings['includeAllDescendents']) {
            $this->pageListPlus->filterByPath(Page::getByID($parentPageId)->getCollectionPath());
        } else {
            $this->pageListPlus->filterByParentID($parentPageId);
        }
    }

    private function setIgnorePermissions()
    {
        if (!isset($this->settings['ignorePermissions'])) {
            return;
        }
        $ignorePermissions = $this->settings['ignorePermissions'];
        if ($ignorePermissions) {
            $this->pageListPlus->ignorePermissions();
        }
    }

    private function setDisplayAliases()
    {
        if (!isset($this->settings['displayAliases'])) {
            return;
        }
        $displayAliases = $this->settings['displayAliases'];
        if ($displayAliases) {
            $this->pageListPlus->includeAliases();
        }
    }

    private function setSearchCriteria()
    {
        if (!isset($this->settings['useForSearch']) || !$this->settings['useForSearch']) {
            return;
        }
        $this->setSearchForm();
        $this->setSearchResults();
    }

    private function setSearchForm()
    {
        if (!isset($this->settings['showSearchForm']) || !$this->settings['showSearchForm']) {
            return;
        }
        $this->setSearchForm_QueryBox();
        $this->setSearchForm_Submission();
        $this->setSearchForm_Filters();
    }

    private function setSearchForm_QueryBox()
    {
        if (!isset($this->settings['showSearchBox']) || !$this->settings['showSearchBox']) {
            return;
        }
        $this->pageListPlus->showSearchBox = true;
        $this->pageListPlus->searchBoxButtonText = $this->settings['searchBoxButtonText'] ? $this->settings['searchBoxButtonText'] : t('Search');
        $this->pageListPlus->searchBoxTargetURL = $this->settings['searchBoxTargetURL'];
    }

    private function setSearchForm_Submission()
    {
        $this->pageListPlus->submitOnChangeOfFilter = $this->settings['submitOnChangeOfFilter'] ? true : false;
        $this->pageListPlus->submitViaAjax = $this->settings['submitViaAjax'] ? true : false;
    }

    private function setSearchForm_Filters()
    {
        if (!isset($this->settings['showSearchFilters']) || !$this->settings['showSearchFilters']) {
            return;
        }
        $this->pageListPlus->showSearchFilters = true;
    }

    private function setSearchResults()
    {
        if (!isset($this->settings['showSearchResults']) || !$this->settings['showSearchResults']) {
            return;
        }
        $this->setSearchResults_Receiving();
    }

    private function setSearchResults_Receiving()
    {
        $this->pageListPlus->receiveViaAjax = $this->settings['receiveViaAjax'] ? true : false;
    }

    private function useSimpleSearch() {
        return ($this->settings['resultsRelatedTo'] && $this->settings['useFulltextSearch']) ? false : true;
    }
    private function filterByQuery()
    {
        if (!isset($_GET['query'])) {
            return;
        }

        $this->settings['query'] = $_GET['query'];

        if ($this->useSimpleSearch()) {
            $this->filterBySimpleKeywords('query');
        } else {
            $this->filterByFulltextKeywords('query');
        }
    }

    private function filterBySimpleKeywords($key = 'keyword')
    {
        $this->pageListPlus->filterByKeywords($this->settings[$key]);
    }

    private function filterByFulltextKeywords($key = 'keyword')
    {
        $keywords = $this->settings[$key];
        if ($this->settings['mysqlFulltextModifier']) {
            if (in_array($this->settings['mysqlFulltextModifier'], ['boolean', 'phrase'])) {
                $this->pageListPlus->setBooleanMode(true);
                if ($this->settings['mysqlFulltextModifier'] == 'phrase') {
                    $keywords = '"' . $this->settings[$key] . '"';
                }
            }
        }
        //print $keywords;die();
        $this->pageListPlus->setExpansionMode(strpos($this->settings['mysqlFulltextModifier'], 'expand') !== false ? true : false);
        $this->pageListPlus->setNaturalLanguageMode(strpos($this->settings['mysqlFulltextModifier'], 'natural') !== false ? true : false);
        $this->pageListPlus->filterByFulltextKeywords($keywords);
        $this->isFulltextSearch = true;
    }

    private function filterByKeywords()
    {
        if (!isset($this->settings['keyword']) || !$this->settings['keyword']) {
            return;
        }

        if ($this->useSimpleSearch()) {
            $this->filterBySimpleKeywords();
        } else {
            $this->filterByFulltextKeywords();
        }
    }

    private function filterByKeywordsRelatedToArea()
    {
        if (!$this->settings['relatedToArea']) {
            return;
        }
        $use_tags = false;
        $area = $this->settings['relatedToArea'];
        if ($this->settings['relatedToArea'] == '-!-all areas-!-') {
            $area = false;
            $use_tags = true;
        }
        $content = strip_tags($this->getBlockContent($this->currentPage, $area));
        if ($use_tags) {
            $tags = $this->currentPage->getAttribute('tags');
            if (is_object($tags)) {
                $content .= " " . str_replace("\n", " ", $tags->__toString());
            }
        }
        $textHelper = new TextHelper();
        $keywords = str_replace('-', ' ', $textHelper->urlify($content, 1000000));
        $this->isFulltextSearch = true;
        $this->pageListPlus->filterByFulltextKeywords($keywords);
    }

    private function getBlockContent($page, $areaHandle = false)
    {
        $skipBlockTypeHandles = ['youtube', 'video', 'next_previous', 'horizontal_rule', 'search', 'page_list_plus', 'page_list_map_settings', 'geocode_map'];
        $content = "";

        $blocks = $page->getBlocks();
        $request = Request::getInstance();
        foreach ($blocks as $block) {
            $blockContent = "";
            $blockAreaHandle = $block->getAreaHandle();
            if ((!$areaHandle && (stripos($blockAreaHandle, 'Header') === false || stripos($blockAreaHandle, 'Footer') === false)) || ($areaHandle == $blockAreaHandle)) {
                $blockTypeHandle = $block->getBlockTypeHandle();

                if (!in_array($blockTypeHandle, $skipBlockTypeHandles)) {
                    $blockController = $block->getController();
                    if (method_exists($blockController, 'getSearchableContent')) {
                        $blockContent = $blockController->getSearchableContent();
                    } elseif (method_exists($blockController, 'getContent')) {
                        $blockContent = $blockController->getContent();
                    } elseif (method_exists($blockController, 'view')) {
                        ob_start();
                        $request->setCurrentPage($block->getBlockCollectionObject());
                        $block->display();
                        $blockContent = ob_get_contents();
                        ob_end_clean();
                        $request->setCurrentPage($page);
                    }
                }
                $content .= $blockContent;
            }
        }
        return $content;
    }

    private function filterOutCurrentPage()
    {
        if (!isset($this->settings['hideCurrentPage']) || !$this->settings['hideCurrentPage']) {
            return;
        }
        $this->updateCurrentPage();
        $this->pageListPlus->filterByClause("p.cID!='" . $this->settings['current_page'] . "'");
    }

    private function updateCurrentPage()
    {
        if (!isset($this->settings['current_page']) || is_null($this->settings['current_page'])) {
            $this->settings['current_page'] = 1;
        }
    }

    private function setCustomAttributeFilter()
    {
        if (!isset($this->settings['pageAttributesUsedForFilter']) || !$this->settings['pageAttributesUsedForFilter'] || count($this->settings['pageAttributesUsedForFilter']) < 1) {
            return;
        }

        $this->settings['pageAttributeIdsUsedInSearch'] = $this->settings['pageAttributeIdsUsedInSearch'] ? $this->settings['pageAttributeIdsUsedInSearch'] : [];
        $this->settings['pageAttributeIdsUsedInSearch'] = is_array($this->settings['pageAttributeIdsUsedInSearch']) ? $this->settings['pageAttributeIdsUsedInSearch'] : [$this->settings['pageAttributeIdsUsedInSearch']];
        if ($this->isPreview || (!$this->isPreview && count($this->settings['pageAttributeIdsUsedInSearch']) > 0)) {
            foreach (array_keys($this->settings['pageAttributesUsedForFilter']) as $attributeId) {
                if (!in_array($attributeId, $this->settings['pageAttributeIdsUsedInSearch'])
                    && (!is_array($this->settings['pageAttributeId']) || !in_array($attributeId, $this->settings['pageAttributeId']))
                ) {
                    unset($this->settings['pageAttributesUsedForFilter'][$attributeId]);
                }
            }
        }

        $searchFilters = $this->getSearchValues($this->settings['searchDefaults']);
        $attributeFilters = $this->settings['pageAttributesUsedForFilter'];

        foreach ($attributeFilters as $pageAttributeFilterId => $attributeFilter) {
            if (is_numeric($pageAttributeFilterId)) {
                $pageAttribute = CollectionAttributeKey::getByID($pageAttributeFilterId);
                $attributeFilter['isStandardProperty'] = false;
            } elseif (in_array($pageAttributeFilterId, ['cID', 'cvName', 'cvDescription', 'cDatePublic', 'cvDatePublic', 'cDateModified', 'cvDateCreated', 'uID', 'cvAuthorUID', 'cvApproverUID'])) {
                $pageAttribute = false;
                $attributeFilter['isStandardProperty'] = true;
            }
            if (!is_object($pageAttribute) && !$attributeFilter['isStandardProperty']) {
                continue;
            }

            $dateFormat = 'Y-m-d H:i:s';
            $attributeFilter['isDate'] = false;
            $attributeFilter['handle'] = '';
            $attributeFilter['dateDisplayMode'] = 'date_time';
            $attributeFilter['dateFormat'] = null;
            if ($attributeFilter['isStandardProperty']) {
                $attributeFilter['currentValue'] = $this->getStandardPropertyAttributeValue($pageAttributeFilterId);
                $attributeFilter['handle'] = $this->getStandardPropertyAttributeHandle($pageAttributeFilterId);
                $attributeFilter['isDate'] = $this->getStandardPropertyAttributeIsDate($pageAttributeFilterId);
            } else {
                $attributeFilter['currentValue'] = $this->currentPage->getCollectionAttributeValue($pageAttribute);
                $attributeFilter['handle'] = 'ak_' . $pageAttribute->getAttributeKeyHandle();
                if ($pageAttribute->getAttributeTypeHandle() == "date_time") {
                    $attributeFilter['isDate'] = true;
                    $dateDisplayMode = Database::getActiveConnection()->GetOne('select akDateDisplayMode from atDateTimeSettings where akID = ?', $pageAttribute->getAttributeKeyID());
                    $dateDisplayMode = $dateDisplayMode == '' ? 'date_time' : $dateDisplayMode;
                    $attributeFilter['dateDisplayMode'] = $dateDisplayMode == '' ? 'date_time' : $dateDisplayMode;
                    $attributeFilter['dateFormat'] = $dateDisplayMode == 'date' ? 'Y-m-d' : 'Y-m-d H:i:s';
                } else {
                    $attributeFilter['isDate'] = false;
                }
            }

            if (!is_object($attributeFilter['currentValue']) && !is_array($attributeFilter['currentValue'])) {
                trim($attributeFilter['currentValue']);
                $attributeFilter['currentValue'] = $this->escape($attributeFilter['currentValue']);
            }

            if (isset($attributeFilter['val1'])) {
                $attributeFilter['val1'] = $this->getEscapedAttributeFilterValue($attributeFilter, 'val1');
            }
            if (isset($attributeFilter['val2'])) {
                $attributeFilter['val2'] = $this->getEscapedAttributeFilterValue($attributeFilter, 'val2');
            }
            if (isset($attributeFilter['val3'])) {
                $attributeFilter['val3'] = $this->getEscapedAttributeFilterValue($attributeFilter, 'val3');
            }

            if (is_object($pageAttribute)) {
                if ($attributeFilter['isDate'] && array_key_exists($pageAttribute->getAttributeKeyID(), $this->searchValues)) {
                    if (is_array($this->searchValues[$pageAttribute->getAttributeKeyID()])) {
                        foreach ($this->searchValues[$pageAttribute->getAttributeKeyID()] as $qsakey => $qsavalue) {
                            trim($this->searchValues[$pageAttribute->getAttributeKeyID()][$qsakey]);
                            $this->searchValues[$pageAttribute->getAttributeKeyID()][$qsakey] = date($dateFormat, strtotime($this->searchValues[$pageAttribute->getAttributeKeyID()][$qsakey]));
                        }
                    } else {
                        trim($this->searchValues[$pageAttribute->getAttributeKeyID()]);
                        $this->searchValues[$pageAttribute->getAttributeKeyID()] = date($dateFormat, strtotime($this->searchValues[$pageAttribute->getAttributeKeyID()]));
                    }
                    $searchFilters[$pageAttribute->getAttributeKeyID()] = $this->searchValues[$pageAttribute->getAttributeKeyID()];
                }
            }

            $filter = null;
            if ($attributeFilter['isStandardProperty']) {
                $filter = Filter::getByHandle('standard');
            } elseif (is_object($pageAttribute)) {
                $filter = Filter::getByHandle($pageAttribute->getAttributeTypeHandle());
            }
            if ($filter) {
                $pageListPlusFilter = $filter->getClass();
                $pageListPlusFilter->setValues($this->pageListPlus, $pageAttribute, $attributeFilter, $attributeFilters, $searchFilters);
                $pageListPlusFilter->run();
            }
        }
    }

    private function getSearchValues($defaults = null)
    {
        $pageAttributes = CollectionAttributeKey::getList();
        if (is_array($pageAttributes)) {
            uasort($pageAttributes, function ($a, $b) {
                if ($a->getAttributeKeyName() == $b->getAttributeKeyName()) {
                    return 0;
                }
                return ($a->getAttributeKeyName() < $b->getAttributeKeyName()) ? -1 : 1;
            });

            $blacklist = PageListPlus::getPageAttributeBlacklist();
            $querystring = $_GET;

            $searchValues = [];
            foreach ($pageAttributes as $pageAttribute) {
                if (!array_key_exists($pageAttribute->getAttributeKeyHandle(), $blacklist)) {
                    if (in_array($pageAttribute->getAttributeTypeHandle(), PageListPlus::getSupportedAttributeTypes())) {
                        if (isset($querystring[$pageAttribute->getAttributeKeyHandle()])) {
                            $akID = $pageAttribute->getAttributeKeyID();
                            $searchValues[$akID] = $querystring[$pageAttribute->getAttributeKeyHandle()];
                        }
                    }
                }
            }
        } else {
            $searchValues = null;
        }

        if ($defaults) {
            if (!is_array($defaults)) {
                $defaults = unserialize($defaults);
            }
            foreach ($defaults as $akID => $value) {
                if (empty($value)) {
                    unset($defaults[$akID]);
                } else {
                    $defaults[$akID] = is_array($value) ? $value : [$value];
                }
            }
        } else {
            $defaults = [];
        }

        if (is_array($searchValues)) {
            $overrideDefaults = [];
            foreach ($searchValues as $searchValueKey => $searchValueValue) {
                if (is_array($searchValueValue)) {
                    foreach ($searchValueValue as $key => $value) {
                        if (empty($value)) {
                            unset($searchValues[$searchValueKey][$key]);
                            if (count($searchValues[$searchValueKey]) == 0) {
                                $overrideDefaults[] = $searchValueKey;
                                unset($searchValues[$searchValueKey]);
                            }
                        }
                    }
                }
            }
        }

        $searchValues = $searchValues + $defaults;
        foreach ($overrideDefaults as $key) {
            unset($searchValues[$key]);
        }

        $this->searchValues = $searchValues;
        return $searchValues;
    }

    private function getStandardPropertyAttributeValue($pageAttributeId = false)
    {
        if ($pageAttributeId == 'uID' || $pageAttributeId == 'cvAuthorUID') {
            return $this->currentPage->getCollectionUserID();
        } elseif ($pageAttributeId == 'cDatePublic' || $pageAttributeId == 'cvDatePublic') {
            return $this->currentPage->getCollectionDatePublic();
        } elseif ($pageAttributeId == 'cDateModified' || $pageAttributeId == 'cvDateCreated') {
            return $this->currentPage->getCollectionDateLastModified();
        } elseif ($pageAttributeId == 'cName' || $pageAttributeId == 'cvName') {
            return $this->currentPage->getCollectionName();
        } elseif ($pageAttributeId == 'cvDescription') {
            return $this->currentPage->getCollectionDescription();
        } elseif ($pageAttributeId == 'cvApproverUID') {
            $collectionVersion = CollectionVersion::get($this->currentPage, 'ACTIVE');
            return $collectionVersion->getVersionApproverUserID();
        } elseif ($pageAttributeId == 'cID') {
            return $this->currentPage->getCollectionID();
        } else {
            return false;
        }
    }

    private function getStandardPropertyAttributeHandle($pageAttributeId = false)
    {
        if ($pageAttributeId == 'uID' || $pageAttributeId == 'cvAuthorUID') {
            return 'p.uID';
        } elseif ($pageAttributeId == 'cDatePublic' || $pageAttributeId == 'cvDatePublic') {
            return 'cvDatePublic';
        } elseif ($pageAttributeId == 'cDateModified' || $pageAttributeId == 'cvDateCreated') {
            return 'cDateModified';
        } elseif ($pageAttributeId == 'cName' || $pageAttributeId == 'cvName') {
            return 'cvName';
        } elseif ($pageAttributeId == 'cvDescription') {
            return 'cvDescription';
        } elseif ($pageAttributeId == 'cvApproverUID') {
            return 'cvApproverUID';
        } elseif ($pageAttributeId == 'cID') {
            return 'p.cID';
        } else {
            return false;
        }
    }

    private function getStandardPropertyAttributeIsDate($pageAttributeId = false)
    {
        if ($pageAttributeId == 'cDatePublic' || $pageAttributeId == 'cvDatePublic' || $pageAttributeId == 'cDateModified' || $pageAttributeId == 'cvDateCreated') {
            return true;
        } else {
            return false;
        }
    }

    private function escape($value)
    {
        return addslashes($value);
    }

    private function getEscapedAttributeFilterValue($attributeFilter, $key)
    {//$val, $isDate, $dateFormat) {
        if (is_array($attributeFilter[$key])) {
            foreach ($attributeFilter[$key] as $k => $v) {
                trim($v);
                if ($attributeFilter['isDate'] && $v) {
                    $v = date($attributeFilter['dateFormat'], strtotime($v));
                }
                $attributeFilter[$key][$k] = $v;
                //$val[$k] = $this->escape($v);
            }
            return $attributeFilter[$key];
        } else {
            trim($attributeFilter[$key]);
            if ($attributeFilter['isDate'] && $attributeFilter[$key]) {
                $attributeFilter[$key] = date($attributeFilter['dateFormat'], strtotime($attributeFilter[$key]));
            }
            return $this->escape($attributeFilter[$key]);
        }
    }

    private function setupSorting()
    {
        if (!isset($this->settings['orderBy']) || !is_array($this->settings['orderBy']) || count($this->settings['orderBy']) < 1) {
            return;
        }
        if (!is_array($this->settings['orderBy'])) {
            $this->settings['orderBy'] = [$this->settings['orderBy']];
        }
        if ($this->settings['orderBy'][0] == 'user_select') {
            if (count($this->settings['userSort']) == 0 || !isset($_GET['orderBy']) || is_array($_GET['orderBy'])) {
                array_shift($this->settings['orderBy']);
                if (count($this->settings['orderBy']) == 0) {
                    return;
                }
            } else {
                $this->settings['orderBy'][0] = $_GET['orderBy'];
            }
        }
        $orderBy = [];
        foreach ($this->settings['orderBy'] as $order) {
            $orderBy[] = $this->getSorting($order);
        }
        $this->settings['orderBy'] = $orderBy;

        foreach ($this->settings['orderBy'] as $key => $order) {
            $this->pageListPlus->sortBy($order['column'], $order['direction'], $key ? false : true);
        }
    }

    private function getSorting($order = null)
    {
        $sorting = false;
        if (empty($order)) {
            return false;
        }
        switch ($order) {
            case 'display_asc':
                $sorting = ['column' => 'p.cDisplayOrder', 'direction' => 'asc'];
                break;
            case 'display_desc':
                $sorting = ['column' => 'p.cDisplayOrder', 'direction' => 'desc'];
                break;
            case 'chrono_asc':
                $sorting = ['column' => 'cvDatePublic', 'direction' => 'asc'];
                break;
            case 'chrono_desc':
                $sorting = ['column' => 'cvDatePublic', 'direction' => 'desc'];
                break;
            case 'modified_asc':
                $sorting = ['column' => 'cvDateCreated', 'direction' => 'asc'];
                break;
            case 'modified_desc':
                $sorting = ['column' => 'cvDateCreated', 'direction' => 'desc'];
                break;
            case 'alpha_asc':
                $sorting = ['column' => 'cvName', 'direction' => 'asc'];
                break;
            case 'alpha_desc':
                $sorting = ['column' => 'cvName', 'direction' => 'desc'];
                break;
            case 'relevance_desc':
                if ($this->isFulltextSearch) {
                    $sorting = ['column' => 'cIndexScore', 'direction' => 'desc'];
                } else {
                    $sorting = ['column' => 'p.cDisplayOrder', 'direction' => 'asc'];
                }
                break;
            case 'relevance_asc':
                if ($this->isFulltextSearch) {
                    $sorting = ['column' => 'cIndexScore', 'direction' => 'asc'];
                } else {
                    $sorting = ['column' => 'p.cDisplayOrder', 'direction' => 'desc'];
                }
                break;
            case 'random':
                $sorting = ['column' => 'rand()', 'direction' => 'asc'];
                break;
            default:
                //Ideal Code
                $db = Database::getActiveConnection();
                $direction = substr($order, strrpos($order, '_') + 1);
                $column = 'ak_' . substr($order, 0, strrpos($order, '_'));
                $collectionAttributeKey = new CollectionAttributeKey();
                $columns = $db->MetaColumns($collectionAttributeKey->getIndexedSearchTable());
                if (isset($columns[$column])) {
                    $sorting = ['column' => $column, 'direction' => $direction];
                } elseif (isset($columns[$column . '_percentage'])) {
                    $sorting = ['column' => $column . '_percentage ', 'direction' => $direction];
                } else {
                    return false;
                }
                break;
        }
        return $sorting;
    }

    private function setItemsPerPage()
    {
        $this->pageListPlus->numberOfResults = (int)$this->settings['numberOfResults'];
        $this->pageListPlus->setItemsPerPage($this->pageListPlus->numberOfResults >= 0 ? $this->pageListPlus->numberOfResults : -1);
    }

    private function skipTopResults() {
        $this->pageListPlus->skipTopNumberOfResults = (int) $this->settings['skipTopNumberOfResults'];
        $this->pageListPlus->skipTopNumberOfResults = $this->pageListPlus->skipTopNumberOfResults >= 0 ? $this->pageListPlus->skipTopNumberOfResults : 0;
        if ($this->pageListPlus->skipTopNumberOfResults) {
            $skipPageListPlus = clone $this->pageListPlus;
            $skipPageListPlus->setItemsPerPage($skipPageListPlus->skipTopNumberOfResults);
            $skipPageListPlus->skipTopNumberOfResults = 0;
            $pagination = $skipPageListPlus->getPagination();
            $skipResults = $pagination->getCurrentPageResults();
            if (count($skipResults)>0) {
                $skipPageIds = [];
                foreach ($skipResults as $skipPage) {
                    $skipPageIds[] = $skipPage->getCollectionID();
                }
                $this->pageListPlus->filterOutByPageId($skipPageIds);
            }
        }
    }

    private function setupRss()
    {
        if (!isset($this->settings['provideRssFeed']) || !$this->settings['provideRssFeed']) {
            return;
        }
        $this->pageListPlus->setFeedTitle($this->settings['rssFeedTitle']);
        $this->pageListPlus->setFeedDescription($this->settings['rssFeedDescription']);
    }

    public function merge($querystringAttributes, $defaults)
    {
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
        return $defaults;
    }

    private function getKeywords($content)
    {
        $stopWords = ["a", "about", "above", "after", "again", "against", "all", "am", "an", "and", "any", "are", "aren't", "as", "at", "be", "because", "been", "before", "being", "below", "between", "both", "brings", "but", "by", "can't", "cannot", "could", "couldn't", "did", "didn't", "do", "does", "doesn't", "doing", "don't", "down", "during", "each", "few", "for", "from", "further", "had", "hadn't", "has", "hasn't", "have", "haven't", "having", "he", "he'd", "he'll", "he's", "her", "here", "here's", "hers", "herself", "him", "himself", "his", "how", "how's", "i", "i'd", "i'll", "i'm", "i've", "if", "in", "into", "is", "isn't", "it", "it's", "its", "itself", "like", "let's", "me", "more", "most", "much", "mustn't", "my", "myself", "nbsp", "no", "nor", "not", "of", "off", "on", "once", "only", "or", "other", "ought", "our", "ours", "ourselves", "out", "over", "own", "same", "shan't", "she", "she'd", "she'll", "she's", "should", "shouldn't", "so", "some", "such", "than", "that", "that's", "the", "their", "theirs", "them", "themselves", "then", "there", "there's", "these", "they", "they'd", "they'll", "they're", "they've", "this", "those", "through", "to", "too", "under", "until", "up", "very", "was", "wasn't", "we", "we'd", "we'll", "we're", "we've", "were", "weren't", "what", "what's", "when", "when's", "where", "where's", "which", "while", "who", "who's", "whom", "why", "why's", "with", "won't", "would", "wouldn't", "www", "you", "you'd", "you'll", "you're", "you've", "your", "yours", "yourself", "yourselves"];

        $content = preg_replace('/ss+/i', '', $content);
        $content = trim($content);
        $content = preg_replace('/[^a-zA-Z0-9 -]/', ' ', $content);
        $content = strtolower($content);

        preg_match_all('/\b.*?\b/i', $content, $words);
        $words = $words[0];

        foreach ($words as $key => $item) {
            if ($item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 3) {
                unset($words[$key]);
            }
        }

        $countedWords = [];

        if (is_array($words)) {
            foreach ($words as $val) {
                $val = trim($val);
                if ($val) {
                    $val = strtolower($val);
                    if (isset($countedWords[$val])) {
                        $countedWords[$val]++;
                    } else {
                        $countedWords[$val] = 1;
                    }
                }
            }
        }

        arsort($countedWords);
        $keywords = array_slice($countedWords, 0, 5);
        print "<!-- " . implode(', ', array_keys($keywords)) . " -->";
        return array_keys($keywords);
    }
}
