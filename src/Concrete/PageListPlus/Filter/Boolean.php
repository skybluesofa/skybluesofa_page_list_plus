<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter;

use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Filter;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;

defined('C5_EXECUTE') or die("Access Denied.");

class Boolean extends Contract\FilterContract
{

    public function run()
    {
        if (strpos($this->currentAttributeFilter['filterSelection'], 'attribute') !== false) {
            if ($this->currentAttributeFilter['filterSelection'] == 'equals_attribute') {
                $this->pageListPlus->filterByClause("/* boolean */ (" . $this->currentAttributeFilter['handle'] . "=ak_" . $this->currentAttributeFilter['val1'] . ")");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_equals_attribute') {
                $this->pageListPlus->filterByClause("/* boolean */ (" . $this->currentAttributeFilter['handle'] . "!=ak_" . $this->currentAttributeFilter['val1'] . ")");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than_attribute') {
                $this->pageListPlus->filterByClause("/* boolean */ (" . $this->currentAttributeFilter['handle'] . "<ak_" . $this->currentAttributeFilter['val1'] . ")");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than_or_equals_attribute') {
                $this->pageListPlus->filterByClause("/* boolean */ (" . $this->currentAttributeFilter['handle'] . "<=ak_" . $this->currentAttributeFilter['val1'] . ")");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'greater_than_attribute') {
                $this->pageListPlus->filterByClause("/* boolean */ (" . $this->currentAttributeFilter['handle'] . ">ak_" . $this->currentAttributeFilter['val1'] . ")");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'greater_than_or_equals_attribute') {
                $this->pageListPlus->filterByClause("/* boolean */ (" . $this->currentAttributeFilter['handle'] . ">=ak_" . $this->currentAttributeFilter['val1'] . ")");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == "matches_all") {
            $this->pageListPlus->filterByAttribute($this->pageAttribute->getAttributeKeyHandle(), $this->currentAttributeFilter['currentValue'] ? 1 : 0);
        } elseif ($this->currentAttributeFilter['filterSelection'] == "querystring_all") {
            if (array_key_exists($this->pageAttribute->getAttributeKeyID(), $this->searchFilters)) {
                if (isset($this->searchFilters[$this->pageAttribute->getAttributeKeyID()]) && !empty($this->searchFilters[$this->pageAttribute->getAttributeKeyID()])) {
                    $matchVal = 1;
                    if (!$this->searchFilters[$this->pageAttribute->getAttributeKeyID()] || $this->searchFilters[$this->pageAttribute->getAttributeKeyID()] == 'false') {
                        $matchVal = 0;
                    }
                    $this->pageListPlus->filterByAttribute($this->pageAttribute->getAttributeKeyHandle(), $matchVal);
                }
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == "not_matches_all") {
            $this->pageListPlus->filterByAttribute($this->pageAttribute->getAttributeKeyHandle(), $this->currentAttributeFilter['currentValue'] ? 0 : 1);
        } elseif ($this->currentAttributeFilter['filterSelection'] == "not_querystring_all") {
            if (array_key_exists($this->pageAttribute->getAttributeKeyID(), $this->searchFilters)) {
                if (isset($this->searchFilters[$this->pageAttribute->getAttributeKeyID()]) && !empty($this->searchFilters[$this->pageAttribute->getAttributeKeyID()])) {
                    $matchVal = 0;
                    if (!$this->searchFilters[$this->pageAttribute->getAttributeKeyID()] || $this->searchFilters[$this->pageAttribute->getAttributeKeyID()] == 'false') {
                        $matchVal = 1;
                    }
                    $this->pageListPlus->filterByAttribute($this->pageAttribute->getAttributeKeyHandle(), $matchVal);
                }
            }
        } else {
            $this->pageListPlus->filterByAttribute($this->pageAttribute->getAttributeKeyHandle(), $this->currentAttributeFilter['filterSelection'] == 'true' ? 1 : 0);
        }
    }
}
