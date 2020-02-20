<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Contract;

use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;

defined('C5_EXECUTE') or die("Access Denied.");

abstract class FilterContract
{
    protected $pageListPlus;
    protected $pageAttribute;
    protected $currentAttributeFilter;
    protected $attributeFilters;
    protected $searchFilters;

    function setValues(PageListPlus $pageListPlus, $pageAttribute, $currentAttributeFilter, $attributeFilters, $searchFilters)
    {
        $this->pageListPlus = $pageListPlus;
        $this->pageAttribute = $pageAttribute;
        $this->currentAttributeFilter = $currentAttributeFilter;
        $this->attributeFilters = $attributeFilters;
        $this->searchFilters = $searchFilters;
    }

    abstract function run();

    protected function escape($value)
    {
        return addslashes($value);
    }

    protected function runDateFilter()
    {
        if ($this->currentAttributeFilter['filterSelection'] == 'not_empty') {
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_empty') {
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'past') {
            $test_date = date('Y-m-d') . " 00:00:00";
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . "<'" . $test_date . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'future') {
            $test_date = date('Y-m-d', strtotime('+1 day')) . " 00:00:00";
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $test_date . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_future') {
            $test_date = date('Y-m-d') . " 23:59:59";
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . "<='" . $test_date . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'today_and_future') {
            $test_date = date('Y-m-d') . " 00:00:00";
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $test_date . "')");
        } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['yesterday', 'today', 'tomorrow'])) {
            $difs = ['yesterday' => '-1 day', 'today' => '+0 days', 'tomorrow' => '+1 day'];
            $test_date = date('Y-m-d', strtotime($difs[$this->currentAttributeFilter['filterSelection']]));
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $test_date . " 00:00:00' AND " . $this->currentAttributeFilter['handle'] . "<='" . $test_date . " 23:59:59')");
        } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['last_year', 'this_year', 'next_year'])) {
            $difs = ['last_year' => '-1 year', 'this_year' => '+0 days', 'next_year' => '+1 year'];
            $test_date = date('Y', strtotime($difs[$this->currentAttributeFilter['filterSelection']]));
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $test_date . "-01-01 00:00:00' AND " . $this->currentAttributeFilter['handle'] . "<='" . $test_date . "-12-31 23:59:59')");
        } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['last_365', 'next_365'])) {
            $difs = ['last_365' => ['-1 year', '+0 days'], 'next_365' => ['+0 days', '+1 year']];
            $start_date = date('Y-m-d', strtotime($difs[$this->currentAttributeFilter['filterSelection']][0]));
            $end_date = date('Y-m-d', strtotime($difs[$this->currentAttributeFilter['filterSelection']][1]));
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $start_date . " 00:00:00' AND " . $this->currentAttributeFilter['handle'] . "<='" . $end_date . " 23:59:59')");
        } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['matches_all', 'not_matches_all', 'more_than', 'more_than_match', 'more_than_or_equal_to', 'more_than_or_equal_to_match', 'less_than', 'less_than_match', 'equals', 'not_equals', 'less_than_or_equal_to', 'less_than_or_equal_to_match'])) {
            $compare = "";
            if (in_array($this->currentAttributeFilter['filterSelection'], ['equals', 'matches_all'])) {
                $compare .= "=";
            } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['not_equals', 'not_matches_all'])) {
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
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . $compare . "'" . $test_date . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'between_inclusive') {
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'between_exclusive') {
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_between_inclusive') {
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val2'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_between_exclusive') {
            $this->pageListPlus->filterByClause("/* Date Filter */ (" . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val2'] . "')");
        }
    }
}
