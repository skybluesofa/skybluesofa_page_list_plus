<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\Block\PageListPlus;

use Concrete\Core\View\View as View;
use Concrete\Core\Html\Service\Html as HtmlHelper;
use Concrete\Core\Html\Service\Navigation as NavigationHelper;
use Concrete\Core\Utility\Service\Validation as ValidationHelper;
use Concrete\Core\Application\Service\Urls as UrlsHelper;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Generator as PageListPlusGenerator;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Service\Finder as FilterFinder;
use Concrete\Core\Area\Area;
use Concrete\Core\Page\Feed as PageFeed;
use Concrete\Core\Page\Page;
use Database;
use Concrete\Core\Http\Request;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{

    public $pageTypeId = [];
    public $pageTemplateId = [];
    public $parentPageId;
    public $includeAllDescendents;
    public $ignorePermissions;
    public $pageAttributes = [];
    public $pageAttributeIdsUsedInSearch = [];
    public $supportedAttributeTypes = [];
    public $pageAttributeBlacklist = [];
    public $pageAreaBlacklist = [];
    public $resultsRelatedTo = null;
    public $pageAttributesUsedForFilter = [];
    public $showSearchSelectsAsCheckbox = false;
    public $showSearchSelectAsCheckboxAttributes = '';
    public $showSearchFilterTitles = true;
    public $nameAsSearchFilterAllText = false;
    public $searchFilterAllText = 'Show All';
    public $searchBoxTargetURL = null;
    public $useForSearch = false;
    public $searchDefaults = '';
    public $submitOnChangeOfFilter = false;
    public $submitViaAjax = false;
    public $keyword = null;
    public $querystringSearch = null;
    public $query = null;
    public $relatedToArea = null;
    public $areas = [];
    public $orderBy = null;
    public $userSort = null;
    public $includeThumbnail = false;
    public $includeName = true;
    public $includeDescription = true;
    public $thumbnailHandles = null;
    public $pageListTitle;
    public $showResults;
    public $numberOfResults;
    public $skipFirstNumberOfResults;
    public $noResultsText;
    public $showAllResultsOnLoad;
    public $noResultsTextOnSearchLoad;
    public $truncateSummaries;
    public $truncateLength;
    public $showSeeAllLink;
    public $seeAllLinkText;
    public $seeAllLinkUrl;
    public $paginateResults;
    public $provideRssFeed;
    public $pfID;
    public $rssFeedTitle;
    public $rssHandle;
    public $rssFeedDescription;
    public $showDebugInformation;
    public $debugInformation;
    public $showDebugInformationLocation = 'console';
    public $sortingOptions = [];
    public $pages = [];
    protected $btDescription = "List pages based on type, area and page attributes.";
    protected $btName = "Page List+";
    protected $btHandle = "page_list_plus";
    protected $btInterfaceWidth = "1000";
    protected $btInterfaceHeight = "500";
    protected $btTable = 'btPageListPlus';
    protected $_tabCollections;

    public function on_start()
    {
        $cObj = Page::getCurrentPage();
        $this->addFooterItem('<script>var REL_DIR_FILES_TOOLS_PACKAGES="' . REL_DIR_FILES_TOOLS_PACKAGES . '";var sbs_cID="' . $cObj->cID . '";</script>', 'SCRIPT');
        $al = \Concrete\Core\Asset\AssetList::getInstance();
        $al->register('javascript', 'skybluesofa/pagelistplus', 'js/page_list_plus.js', [], 'skybluesofa_page_list_plus');
        $al->registerGroup('skybluesofa/pagelistplus', [
            ['javascript', 'jquery'],
            ['javascript', 'core/events'],
            ['javascript', 'skybluesofa/pagelistplus']
        ]);
    }

    public function getFormTabs()
    {
        $tabCollections = $this->getFormTabCollections();
        $tabs = [];
        foreach ($tabCollections as $title => $tabCollection) {
            $title = t($title);
            $tabs[] = [$tabCollection['id'], $title, $tabCollection['defaultSelected']];
        }
        return $tabs;
    }

    private function getFormTabCollections()
    {
        if (!$this->_tabCollections) {
            $tabCollections = [];
            $tabCollections['Page Selection'] = ['id' => 'plp-page-selection', 'defaultSelected' => true, 'element' => 'page_selection'];
            $tabCollections['Search'] = ['id' => 'plp-search', 'defaultSelected' => false, 'element' => 'search'];
            $tabCollections['Filters'] = ['id' => 'plp-filters', 'defaultSelected' => false, 'element' => 'filters'];
            $tabCollections['Sort'] = ['id' => 'plp-sort', 'defaultSelected' => false, 'element' => 'sort'];
            $tabCollections['Display'] = ['id' => 'plp-display', 'defaultSelected' => false, 'element' => 'display'];
            $this->_tabCollections = $tabCollections;
        }
        return $this->_tabCollections;
    }

    public function getFormCards()
    {
        $tabCollections = $this->getFormTabCollections();
        return $tabCollections;
    }

    public function registerViewAssets($outputContent = '')
    {
        $this->requireAsset('skybluesofa/pagelistplus');
    }

    public function view()
    {
        $this->setupView();
    }

    public function setupView()
    {
        $this->setupFormElements();
        if (!$this->doesShowResultsOnLoad()) {
            $this->pages = [];
        } else {
            if ($this->showDebugInformation) {
                ob_start();
            }
            $settings = get_object_vars($this);
            $pageListPlus = PageListPlus::generate($settings);
            if ($pageListPlus->numberOfResults > 0) {
                $numberOfResults = (int)$pageListPlus->numberOfResults + (int)$pageListPlus->skipTopNumberOfResults;
                $pageListPlus->setItemsPerPage($numberOfResults);
                $pagination = $pageListPlus->getPagination();
                $this->paginationObject = $pagination;
                $this->pagination = $pagination->renderDefaultView();
                $this->pages = $pagination->getCurrentPageResults();
                if ($this->paginateResults) {
                    $this->requireAsset('css', 'core/frontend/pagination');
                }
            } else {
                $this->pages = $pageListPlus->getResults();
            }
            if ($this->showDebugInformation) {
                $this->debugInformation = ob_get_contents();
                ob_end_clean();
            }
        }
    }

    private function doesShowResultsOnLoad()
    {
        if (!$this->useForSearch) {
            return true;
        }

        if (isset($_REQUEST['query'])) {
            $this->query = $_REQUEST['query'];
        }

        $request = Request::getInstance();

        if ($this->showAllResultsOnLoad) {
            return true;
        } elseif (isset($_REQUEST['query']) && !$request->isXmlHttpRequest()) {
            return true;
        }

        if ($request->isXmlHttpRequest()) {
            if (isset($_REQUEST['arHandle'])) {
                return false;
            }
        }

    }

    public function setupFormElements()
    {
        $this->pageAttributes = $this->getPageAttributes();
        $this->supportedAttributeTypes = PageListPlus::getSupportedAttributeTypes();
        $this->sortingOptions = PageListPlus::getSortingOptions();
        $this->pageAttributeBlacklist = PageListPlus::getPageAttributeBlacklist();
        $this->pageAreaBlacklist = PageListPlus::getPageAreaBlacklist();
        $this->pageTypeId = (!$this->pageTypeId) ? [] : $this->unserialize($this->pageTypeId);
        $this->pageTemplateId = (!$this->pageTemplateId) ? [] : $this->unserialize($this->pageTemplateId);
        $this->pageThemeId = (!$this->pageThemeId) ? [] : $this->unserialize($this->pageThemeId);
        $this->showSearchSelectAsCheckboxAttributes = !$this->showSearchSelectAsCheckboxAttributes ? [] : explode(',', $this->showSearchSelectAsCheckboxAttributes);
        $this->pageAttributeIdsUsedInSearch = !$this->pageAttributeIdsUsedInSearch ? [] : explode(',', $this->pageAttributeIdsUsedInSearch);
        $this->pageAttributesUsedForFilter = !$this->pageAttributesUsedForFilter ? [] : $this->unserialize($this->pageAttributesUsedForFilter);
        $this->searchDefaults = !$this->searchDefaults ? [] : $this->unserialize($this->searchDefaults);
        $this->orderBy = !$this->orderBy ? ['display_asc', 'display_asc', 'display_asc'] : $this->unserialize($this->orderBy);
        $this->userSort = !$this->userSort ? [] : $this->unserialize($this->userSort);
        $this->thumbnailHandles = !$this->thumbnailHandles ? ['thumbnail'] : $this->unserialize($this->thumbnailHandles);

        $areaObject = new Area('Main');
        $this->areas = array_diff($areaObject->getHandleList(), $this->pageAreaBlacklist);
        $this->setupExistingFeeds();
    }

    public function getPageAttributes()
    {
        $pageAttributes = CollectionAttributeKey::getList();
        if (is_array($pageAttributes)) {
            uasort($pageAttributes, function ($a, $b) {
                if ($a->getAttributeKeyName() == $b->getAttributeKeyName()) {
                    return 0;
                }
                return ($a->getAttributeKeyName() < $b->getAttributeKeyName()) ? -1 : 1;
            });
        }
        return $pageAttributes;
    }

    public function unserialize($value)
    {
        return is_array($value) ? $value : unserialize($value);
    }

    public function setupExistingFeeds()
    {
        $pageFeeds = PageFeed::getList();
        $feedHandles = [];
        foreach ($pageFeeds as $pageFeed) {
            if ($pageFeed->getID() != $this->pfID) {
                $feedHandles[] = $pageFeed->getHandle();
            }
        }
        $this->set('feedHandles', $feedHandles);
    }

    public function add()
    {
        $this->setupFormElements();
        $this->includeName = true;
        $this->includeDescription = true;
    }

    public function edit()
    {
        $this->setupFormElements();
        if ($this->pfID) {
            $feed = PageFeed::getByID($this->pfID);
            if (is_object($feed)) {
                $this->set('rssFeed', $feed);
            }
        }
    }

    public function save($args = [])
    {
        $args = $this->normalizeForm($args);
        parent::save($args);
    }

    public function normalizeForm($args = [])
    {
        $args['includeAllDescendents'] = isset($args['includeAllDescendents']) ? 1 : 0;
        $args['ignorePermissions'] = isset($args['ignorePermissions']) ? 1 : 0;
        $args['displayAliases'] = isset($args['displayAliases']) ? 1 : 0;
        $args['useForSearch'] = isset($args['useForSearch']) ? 1 : 0;
        $args['showSearchForm'] = isset($args['showSearchForm']) ? 1 : 0;
        $args['showSearchBox'] = isset($args['showSearchBox']) ? 1 : 0;
        $args['submitOnChangeOfFilter'] = isset($args['submitOnChangeOfFilter']) ? 1 : 0;
        $args['submitViaAjax'] = isset($args['submitViaAjax']) ? 1 : 0;
        $args['showSearchFilters'] = isset($args['showSearchFilters']) ? 1 : 0;
        $args['showSearchFilterTitles'] = isset($args['showSearchFilterTitles']) ? 1 : 0;
        $args['nameAsSearchFilterAllText'] = isset($args['nameAsSearchFilterAllText']) ? 1 : 0;
        $args['alphabetizeSearchSelects'] = isset($args['alphabetizeSearchSelects']) ? 1 : 0;
        $args['showSearchSelectsAsCheckbox'] = isset($args['showSearchSelectsAsCheckbox']) ? 1 : 0;
        $args['showSearchResults'] = isset($args['showSearchResults']) ? 1 : 0;
        $args['showAllResultsOnLoad'] = isset($args['showAllResultsOnLoad']) ? 1 : 0;
        $args['receiveViaAjax'] = isset($args['receiveViaAjax']) ? 1 : 0;
        $args['hideSortTitle'] = isset($args['hideSortTitle']) ? 1 : 0;
        $args['hideCurrentPage'] = isset($args['hideCurrentPage']) ? 1 : 0;
        $args['useFulltextSearch'] = isset($args['useFulltextSearch']) ? 1 : 0;
        $args['truncateSummaries'] = isset($args['truncateSummaries']) ? 1 : 0;
        $args['truncateLength'] = isset($args['truncateLength']) ? $args['truncateLength'] : 0; //Save the Truncate length!!
        $args['showSeeAllLink'] = isset($args['showSeeAllLink']) ? 1 : 0;
        $args['includeThumbnail'] = isset($args['includeThumbnail']) ? 1 : 0;
        $args['includeName'] = isset($args['includeName']) ? 1 : 0;
        $args['includeDescription'] = isset($args['includeDescription']) ? 1 : 0;
        $args['includeDate'] = isset($args['includeDate']) ? 1 : 0;
        $args['useButtonForLink'] = isset($args['useButtonForLink']) ? 1 : 0;
        $args['paginateResults'] = isset($args['paginateResults']) ? 1 : 0;
        $args['provideRssFeed'] = isset($args['provideRssFeed']) ? 1 : 0;
        $args['showDebugInformation'] = isset($args['showDebugInformation']) ? 1 : 0;

        $args['numberOfResults'] = intval($args['numberOfResults']);
        if (!$args['numberOfResults']) {
            $args['numberOfResults'] = 10;
        }

        if (!$args['skipTopNumberOfResults']) {
            $args['skipTopNumberOfResults'] = 0;
        }

        if (isset($args['pageTypeId']) && is_array($args['pageTypeId']) && count($args['pageTypeId']) && !in_array('-1', $args['pageTypeId'])) {
            $args['pageTypeId'] = serialize($args['pageTypeId']);
        } else {
            $args['pageTypeId'] = null;
        }
        if (isset($args['pageTemplateId']) && is_array($args['pageTemplateId']) && count($args['pageTemplateId']) && !in_array('-1', $args['pageTemplateId'])) {
            $args['pageTemplateId'] = serialize($args['pageTemplateId']);
        } else {
            $args['pageTemplateId'] = null;
        }
        if (isset($args['pageThemeId']) && is_array($args['pageThemeId']) && count($args['pageThemeId']) && !in_array('-1', $args['pageThemeId'])) {
            $args['pageThemeId'] = serialize($args['pageThemeId']);
        } else {
            $args['pageThemeId'] = null;
        }

        if (isset($args['pageAttributesUsedForFilter']) && is_array($args['pageAttributesUsedForFilter']) && count($args['pageAttributesUsedForFilter'])) {
            $args['pageAttributeId'] = $args['pageAttributeId'] ? $args['pageAttributeId'] : [];
            foreach (array_keys($args['pageAttributesUsedForFilter']) as $attributeId) {
                if (!in_array($attributeId, $args['pageAttributeId'])) {
                    unset($args['pageAttributesUsedForFilter'][$attributeId]);
                }
            }
            $args['pageAttributesUsedForFilter'] = serialize($args['pageAttributesUsedForFilter']);
        } else {
            $args['pageAttributesUsedForFilter'] = null;
        }
        if (isset($args['resultsRelatedTo'])) {
            if (!in_array($args['resultsRelatedTo'], ['keywords', 'area'])) {
                $args['keyword'] = null;
                $args['relatedToArea'] = null;
            } elseif ($args['resultsRelatedTo'] == 'keywords') {
                $args['relatedToArea'] = null;
            } elseif ($args['resultsRelatedTo'] == 'area') {
                $args['keyword'] = null;
            }
        } else {
            $args['keyword'] = null;
            $args['relatedToArea'] = null;
        }
        if (isset($args['showSearchSelectAsCheckboxAttributes']) && is_array($args['showSearchSelectAsCheckboxAttributes']) && count($args['showSearchSelectAsCheckboxAttributes'])) {
            $args['showSearchSelectAsCheckboxAttributes'] = implode(',', $args['showSearchSelectAsCheckboxAttributes']);
        } else {
            $args['showSearchSelectAsCheckboxAttributes'] = null;
        }
        $args = self::normalizeTargetUrls($args);
        if (isset($args['searchDefaults']) && is_array($args['searchDefaults']) && count($args['searchDefaults'])) {
            $args['searchDefaults'] = serialize($args['searchDefaults']);
        } else {
            $args['searchDefaults'] = null;
        }
        if (isset($args['orderBy']) && is_array($args['orderBy']) && count($args['orderBy'])) {
            $args['orderBy'] = serialize($args['orderBy']);
        } else {
            $args['orderBy'] = null;
        }
        if (isset($args['userSort']) && is_array($args['userSort']) && count($args['userSort'])) {
            $args['userSort'] = serialize($args['userSort']);
        } else {
            $args['userSort'] = null;
        }
        if (isset($args['thumbnailHandles']) && $args['thumbnailHandles']) {
            $args['thumbnailHandles'] = explode(',', $args['thumbnailHandles']);
            $handles = [];
            foreach ($args['thumbnailHandles'] as $handle) {
                trim($handle);
                if ($handle) {
                    $handles[] = $handle;
                }
            }
            $args['thumbnailHandles'] = serialize($handles);
        } else {
            $args['thumbnailHandles'] = serialize(array('thumbnail'));
        }

        $args['pageAttributeIdsUsedInSearch'] = isset($args['pageAttributeIdsUsedInSearch']) ? implode(',', $args['pageAttributeIdsUsedInSearch']) : 0;

        $args = self::normalizeRss($args);

        if (!$args['rssFeedDescription']) {
            $args['rssFeedDescription'] = t('The description of the feed.');
        }
        return $args;
    }

    public function normalizeTargetUrls($args)
    {
        $urlFields = ['searchBoxTargetURL', 'seeAllLinkUrl'];
        foreach ($urlFields as $urlField) {
            if ($args[$urlField]) {
                $targetUrl = $args[$urlField];
                if (is_numeric($targetUrl)) {
                    $targetPage = Page::getByID($targetUrl);
                    if ($targetPage) {
                        $targetUrl = $targetPage ? NavigationHelper::getLinkToCollection($targetPage) : '';
                    }
                } elseif (strpos($targetUrl, 'http://') !== 0 && strpos($targetUrl, 'https://') !== 0) {
                    if (strpos($targetUrl, '/') !== 0) {
                        $targetUrl = 'http://' . $targetUrl;
                    }
                }
            }
            $args[$urlField] = $targetUrl;
        }
        return $args;
    }

    public function normalizeRss($args)
    {
        $pfID = intval($args['pfID']);

        if ($args['provideRssFeed']) {
            if ($pfID) {
                $pf = PageFeed::getByID($pfID);
            }

            if (!is_object($pf)) {
                $pf = new \Concrete\Core\Page\Feed();
                $pf->setTitle($args['rssFeedTitle']);
                $pf->setDescription($args['rssFeedDescription']);
                $pf->setHandle($args['rssHandle']);
            }

            $pf->setParentID(1);
            $pf->setPageTypeID(1);
            $pf->setIncludeAllDescendents(1);
            $pf->setDisplayAliases(1);
            $pf->setDisplayFeaturedOnly(1);
            $pf->displayShortDescriptionContent();
            $pf->save();
            $pfID = $pf->getID();
        } else {
            $pf = PageFeed::getByID($pfID);
            if (is_object($pf)) {
                $pf->delete();
            }
            $pfID = null;
        }

        $args['pfID'] = intval($pfID);
        return $args;
    }

    public function getThumbnailAttribute($page = null)
    {
        if (!$page) {
            return null;
        }
        foreach ($this->thumbnailHandles as $thumbnailHandle) {
            $thumbnail = $page->getAttribute($thumbnailHandle);
            if (is_object($thumbnail)) {
                return $thumbnail;
            }
        }
        return null;
    }

    public function isBlockEmpty()
    {
        return false;
        $pages = $this->get('pages');
        if ($this->title) {
            return false;
        }
        if (count($pages) == 0) {
            if ($this->noResultsText) {
                return false;
            } else {
                return true;
            }
        } else {
            if ($this->includeName || $this->includeDate || $this->includeThumbnail
                || $this->includeDescription || $this->useButtonForLink
            ) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function getShowSearchForm()
    {
        return ($this->useForSearch && $this->showSearchForm && ($this->showSearchBox || $this->showSortBox || ($this->showSearchFilters && $this->pageAttributeIdsUsedInSearch)));
    }

    public function getSearchFilters()
    {
        $searchFilters = [];
        if ($this->pageAttributeIdsUsedInSearch) {
            $attributeBlacklist = PageListPlus::getPageAttributeBlacklist();
            foreach ((array) $this->pageAttributeIdsUsedInSearch as $pageAttributeIdUsedInSearch) {
                if (array_key_exists($pageAttributeIdUsedInSearch, (array) $this->pageAttributesUsedForFilter)) {
                    $pageAttributeUsedForFilter = $this->pageAttributesUsedForFilter[$pageAttributeIdUsedInSearch];
                    if (strpos($pageAttributeUsedForFilter['filterSelection'], 'querystring') !== false) {
                        $pageAttribute = CollectionAttributeKey::getByID($pageAttributeIdUsedInSearch);
                        if (!array_key_exists($pageAttribute->getAttributeKeyHandle(), $attributeBlacklist)) {
                            if (in_array($pageAttribute->getAttributeTypeHandle(), PageListPlus::getSupportedAttributeTypes())) {
                                $searchFilters[] = $pageAttribute;
                            }
                        }
                        //break;
                    }
                }
            }
        }
        return $searchFilters;
    }

    public function getShowResults()
    {
        return (!$this->useForSearch || ($this->useForSearch && $this->showResults));
    }

    public function getShowRss()
    {
        return (!$this->useForSearch || ($this->useForSearch && $this->showResults)) && count($this->pages) && $this->showRss && $this->rssUrl;
    }

    public function getShowTitle()
    {
        return $this->pageListTitle ? true : false;
    }

    public function getShowSorting()
    {
        $this->orderBy = is_array($this->orderBy) ? $this->orderBy : unserialize($this->orderBy);
        $this->userSort = is_array($this->userSort) ? $this->userSort : unserialize($this->userSort);
        return ($this->orderBy[0] == 'user_select' && count($this->userSort) > 1);
    }

    public function getAttributeFormFilterView($pageAttribute = null, $elementValues = null)
    {
        $filterFinder = FilterFinder::getFilterHtml($pageAttribute);
        return View::element($filterFinder['path'], $elementValues, $filterFinder['packageHandle']);
    }

    public function getAttributeFormSearchView($searchFilter = null)
    {
        $searchFinder = FilterFinder::getSearchHtml($searchFilter);
        return View::element($searchFinder['path'], ['filter' => $searchFilter, 'controller' => $this], $searchFinder['packageHandle']);
    }
}
