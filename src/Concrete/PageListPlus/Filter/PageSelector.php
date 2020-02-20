<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter;

use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Contract\FilterContract;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;
use Concrete\Core\Http\Request;

defined('C5_EXECUTE') or die("Access Denied.");

class PageSelector extends FilterContract
{

    public function run()
    {

        if ($this->currentAttributeFilter['filterSelection'] == 'not_empty') {
            $this->pageListPlus->filterByClause("/* Page Selector */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . "!=0)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_empty') {
            $this->pageListPlus->filterByClause("/* Page Selector */ (" . $this->currentAttributeFilter['handle'] . "='' OR  " . $this->currentAttributeFilter['handle'] . "=0)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'id_is_exactly') {
            $this->pageListPlus->filterByClause("/* Page Selector */ (p1.cID='" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'id_is_not_exactly') {
            $this->pageListPlus->filterByClause("/* Page Selector */ (p1.cID!='" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'id_matches') {
            $this->pageListPlus->filterByClause("/* Page Selector */ (" . $this->currentAttributeFilter['handle'] . "='" . $this->currentPageId() . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'id_not_matches') {
            $this->pageListPlus->filterByClause("/* Page Selector */ (" . $this->currentAttributeFilter['handle'] . "!='" . $this->currentPageId() . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'matches_all') {
            $this->pageListPlus->filterByClause("/* Page Selector */ (" . $this->currentAttributeFilter['handle'] . "='" . $this->currentAttributeFilter['currentValue'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_matches_all') {
            $this->pageListPlus->filterByClause("/* Page Selector */ (" . $this->currentAttributeFilter['handle'] . "!='" . $this->currentAttributeFilter['currentValue'] . "')");
        } else {
            // more intensive db stuff
            $pl2 = new PageList();
            if ($this->currentAttributeFilter['filterSelection'] == 'contains') {
                $pl2->query->andWhere("cvName LIKE '%" . $this->currentAttributeFilter['val1'] . "%'");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_contains') {
                // do a negative search, it should return less pages
                $pl2->query->andWhere("cvName LIKE '%" . $this->currentAttributeFilter['val1'] . "%'");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_exactly') {
                $pl2->query->andWhere("cvName LIKE '" . $this->currentAttributeFilter['val1'] . "'");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'starts_with') {
                $pl2->query->andWhere("cvName LIKE '" . $this->currentAttributeFilter['val1'] . "%'");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'ends_with') {
                $pl2->query->andWhere("cvName LIKE '%" . $this->currentAttributeFilter['val1'] . "'");
            } elseif ($this->currentAttributeFilter['filterSelection'] == "querystring_all") {
                if (array_key_exists($this->pageAttribute->getAttributeKeyID(), $this->querystringAttributes)) {
                    if (isset($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()]) && !empty($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()])) {
                        foreach ($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()] as $this->pageAttributeElement) {
                            $pl2->query->andWhere("cvName LIKE '%" . $this->escape($this->pageAttributeElement) . "%'");
                        }
                    }
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == "not_querystring_all") {
                if (array_key_exists($this->pageAttribute->getAttributeKeyID(), $this->querystringAttributes)) {
                    if (isset($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()]) && !empty($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()])) {
                        foreach ($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()] as $this->pageAttributeElement) {
                            $pl2->query->andWhere("cvName NOT LIKE '%" . $this->escape($this->pageAttributeElement) . "%'");
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
                if ($this->currentAttributeFilter['filterSelection'] == 'not_matches_all' || $this->currentAttributeFilter['filterSelection'] == 'not_querystring_all' || $this->currentAttributeFilter['filterSelection'] == 'not_contains') {
                    $this->pageListPlus->filterByClause("/* Page Selector */ (p1.cID NOT IN (" . implode(',', $filter_page_ids) . "))");
                } else {
                    $this->pageListPlus->filterByClause("/* Page Selector */ (p1.cID IN (" . implode(',', $filter_page_ids) . "))");
                }
            } else {
                if ($this->currentAttributeFilter['filterSelection'] == 'not_matches_all' || $this->currentAttributeFilter['filterSelection'] == 'not_querystring_all' || $this->currentAttributeFilter['filterSelection'] == 'not_contains') {
                    $this->pageListPlus->filterByClause("/* Page Selector */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . "!=0)");
                } else {
                    $this->pageListPlus->filterByClause("/* Page Selector */ (1!=1)");
                }
            }
        }
    }

    private function currentPageId() {
        $request = Request::getInstance();
        return $request->getCurrentPage()->cID ? $request->getCurrentPage()->cID : 1;
    }
}
