<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter;

use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Filter;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Contract\FilterContract;
use Database;
use User;

defined('C5_EXECUTE') or die("Access Denied.");

class Standard extends FilterContract
{
    public function run()
    {
        if (in_array($this->currentAttributeFilter['handle'], ['cvDatePublic', 'cDateModified'])) {
            $this->runDateFilter();
        } elseif (in_array($this->currentAttributeFilter['handle'], ['cvAuthorUID', 'cvApproverUID', 'p.uID'])) {
            $this->runStandardUserFilter();
        } elseif (in_array($this->currentAttributeFilter['handle'], ['cName', 'cvName'])) {
            $this->runStandardPageNameFilter();
        } elseif ($this->currentAttributeFilter['handle'] == 'cvDescription') {
            $this->runStandardPageDescriptionFilter();
        } elseif ($this->currentAttributeFilter['handle'] == 'p.cID') {
            $this->runStandardPageIdFilter();
        }
    }

    private function runStandardUserFilter()
    {
        if ($this->currentAttributeFilter['filterSelection'] == 'equals') {
            $this->pageListPlus->filterByClause("/* User Filter */ (" . $this->currentAttributeFilter['handle'] . "='".$this->currentAttributeFilter['val1']."')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_equals') {
            $this->pageListPlus->filterByClause("/* User Filter */ (" . $this->currentAttributeFilter['handle'] . "!='".$this->currentAttributeFilter['val1']."')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'matches_current') {
            $user = new User();
            $userId = -12345;
            if ($user->uID) {
                $userId = $user->uID;
            }
            $this->pageListPlus->filterByClause("/* User Filter */ (" . $this->currentAttributeFilter['handle'] . "='".$userId."')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_matches_current') {
            $user = new User();
            $userId = -12345;
            if ($user->uID) {
                $userId = $user->uID;
            }
            $this->pageListPlus->filterByClause("/* User Filter */ (" . $this->currentAttributeFilter['handle'] . "!='".$userId."')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'matches_all') {
            $this->pageListPlus->filterByClause("/* User Filter */ (" . $this->currentAttributeFilter['handle'] . "='".$this->currentAttributeFilter['currentValue']."')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_matches_all') {
            $this->pageListPlus->filterByClause("/* User Filter */ (" . $this->currentAttributeFilter['handle'] . "!='".$this->currentAttributeFilter['currentValue']."')");
        }
    }

    private function runStandardPageIdFilter()
    {
        if ($this->currentAttributeFilter['filterSelection'] == 'not_empty') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_empty') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than_match') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['currentValue'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than_or_equal_to') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than_or_equal_to_match') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['currentValue'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_match') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['currentValue'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_or_equal_to') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_or_equal_to_match') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['currentValue'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'between_inclusive') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'between_exclusive') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_between_inclusive') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_between_exclusive') {
            $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'matches_all') {
            if ($this->currentAttributeFilter['currentValue']) {
                $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['currentValue'] . "')");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_matches_all') {
            if ($this->currentAttributeFilter['currentValue']) {
                $this->pageListPlus->filterByClause("/* Page ID Filter */ (" . $this->currentAttributeFilter['handle'] . " NOT LIKE '" . $this->currentAttributeFilter['currentValue'] . "')");
            }
        }
    }

    private function runStandardPageNameFilter()
    {
        if ($this->currentAttributeFilter['filterSelection'] == 'not_empty') {
            $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_empty') {
            $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'contains' && $this->currentAttributeFilter['val1']) {
            $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '%" . $this->currentAttributeFilter['val1'] . "%')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_contains' && $this->currentAttributeFilter['val1']) {
            $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . " NOT LIKE '%" . $this->currentAttributeFilter['val1'] . "%' OR " . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_exactly') {
            if ($this->currentAttributeFilter['val1']) {
                $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['val1'] . "')");
            } else {
                $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_not_exactly') {
            if ($this->currentAttributeFilter['val1']) {
                $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . " NOT LIKE '" . $this->currentAttributeFilter['val1'] . "')");
            } else {
                $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'starts_with') {
            $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['val1'] . "%')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'ends_with') {
            $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '%" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'matches_all') {
            if ($this->currentAttributeFilter['currentValue']) {
                $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['currentValue'] . "')");
            } else {
                $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_matches_all') {
            if ($this->currentAttributeFilter['currentValue']) {
                $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . " NOT LIKE '" . $this->currentAttributeFilter['currentValue'] . "')");
            } else {
                $this->pageListPlus->filterByClause("/* Page Name Filter */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
            }
        }
    }

    private function runStandardPageDescriptionFilter()
    {
        if ($this->currentAttributeFilter['filterSelection'] == 'not_empty') {
            $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_empty') {
            $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'contains' && $this->currentAttributeFilter['val1']) {
            $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '%" . $this->currentAttributeFilter['val1'] . "%')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_contains' && $this->currentAttributeFilter['val1']) {
            $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . " NOT LIKE '%" . $this->currentAttributeFilter['val1'] . "%' OR " . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_exactly') {
            if ($this->currentAttributeFilter['val1']) {
                $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['val1'] . "')");
            } else {
                $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_not_exactly') {
            if ($this->currentAttributeFilter['val1']) {
                $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . " NOT LIKE '" . $this->currentAttributeFilter['val1'] . "')");
            } else {
                $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'starts_with') {
            $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['val1'] . "%')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'ends_with') {
            $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '%" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'matches_all') {
            if ($this->currentAttributeFilter['currentValue']) {
                $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['currentValue'] . "')");
            } else {
                $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_matches_all') {
            if ($this->currentAttributeFilter['currentValue']) {
                $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . " NOT LIKE '" . $this->currentAttributeFilter['currentValue'] . "')");
            } else {
                $this->pageListPlus->filterByClause("/* Page Description Filter */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
            }
        }
    }

    private function runStandardDateFilter()
    {
        if ($this->currentAttributeFilter['filterSelection'] == 'not_empty') {
            $this->pageListPlus->filterByClause("/* Standard Date Filter */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_empty') {
            $this->pageListPlus->filterByClause("/* Standard Date Filter */ (" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'past') {
            $test_date = date('Y-m-d') . " 00:00:00";
            $this->pageListPlus->filterByClause("/* Standard Date Filter */ (" . $this->currentAttributeFilter['handle'] . "<'" . $test_date . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'future') {
            $test_date = date('Y-m-d', strtotime('+1 day')) . " 00:00:00";
            $this->pageListPlus->filterByClause("/* Standard Date Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $test_date . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_future') {
            $test_date = date('Y-m-d') . " 23:59:59";
            $this->pageListPlus->filterByClause("/* Standard Date Filter */ (" . $this->currentAttributeFilter['handle'] . "<='" . $test_date . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'today_and_future') {
            $test_date = date('Y-m-d') . " 00:00:00";
            $this->pageListPlus->filterByClause("/* Standard Date Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $test_date . "')");
        } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['yesterday', 'today', 'tomorrow'])) {
            $difs = ['yesterday' => '-1 day', 'today' => '+0 days', 'tomorrow' => '+1 day'];
            $test_date = date('Y-m-d', strtotime($difs[$this->currentAttributeFilter['filterSelection']]));
            $this->pageListPlus->filterByClause("/* Standard Date Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $test_date . " 00:00:00' AND " . $this->currentAttributeFilter['handle'] . "<='" . $test_date . " 23:59:59')");
        } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['matches_all', 'not_matches_all', 'more_than', 'more_than_match', 'more_than_or_equal_to', 'more_than_or_equal_to_match', 'less_than', 'less_than_match', 'equals', 'not_equals', 'less_than_or_equal_to', 'less_than_or_equal_to_match'])) {
            $compare = "";
            if (in_array($this->currentAttributeFilter['filterSelection'], ['equals','matches_all'])) {
                $compare .= "=";
            } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['not_equals','not_matches_all'])) {
                $compare .= "!=";
            } else {
                $compare = (strpos($this->currentAttributeFilter['filterSelection'], 'less_than') !== false) ? "<" : ">";
                $compare .= (strpos($this->currentAttributeFilter['filterSelection'], 'equal_to') !== false) ? "=" : "";
            }
            if (strpos($this->currentAttributeFilter['filterSelection'], 'match') === false) {
                $test_date = date('Y-m-d H:i:s', strtotime($this->attributeFilters[$this->currentAttributeFilter['handle']]['val1']));
            } else {
                $test_date = date('Y-m-d H:i:s', strtotime($this->currentAttributeFilter['currentValue']));
            }
            $this->pageListPlus->filterByClause("/* Standard Date Filter */ (" . $this->currentAttributeFilter['handle'] . $compare . "'" . $test_date . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'between_inclusive') {
            $this->pageListPlus->filterByClause("/* Standard Date Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'between_exclusive') {
            $this->pageListPlus->filterByClause("/* Standard Date Filter */ (" . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_between_inclusive') {
            $this->pageListPlus->filterByClause("/* Standard Date Filter */ (" . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_between_exclusive') {
            $this->pageListPlus->filterByClause("/* Standard Date Filter */ (" . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val2'] . "')");

        }
    }
}
