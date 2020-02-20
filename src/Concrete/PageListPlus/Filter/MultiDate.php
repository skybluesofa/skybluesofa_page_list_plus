<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter;

use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Filter;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;

defined('C5_EXECUTE') or die("Access Denied.");

class MultiDate extends Contract\FilterContract
{
    public function run()
    {

        if ($this->currentAttributeFilter['filterSelection'] == 'not_empty') {
            $this->filter(false, "(" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_empty') {
            $this->filter(false, "(" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'equals') {
            $test_date = date('Y/m/d', strtotime($this->currentAttributeFilter['filterSelection']));
            $this->filter(false, "(" . $this->currentAttributeFilter['handle'] . " REGEXP '" . $test_date . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_equals') {
            $test_date = date('Y/m/d', strtotime($this->currentAttributeFilter['filterSelection']));
            $this->filter(false, "(" . $this->currentAttributeFilter['handle'] . " NOT REGEXP '" . $test_date . "')");
        } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['yesterday', 'today', 'tomorrow'])) {
            $difs = ['yesterday' => '-1 day', 'today' => '+0 days', 'tomorrow' => '+1 day'];
            $test_date = date('Y/m/d', strtotime($difs[$this->currentAttributeFilter['filterSelection']]));
            $this->filter(false, "(" . $this->currentAttributeFilter['handle'] . " REGEXP '" . $test_date . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'querystring_all' || $this->currentAttributeFilter['filterSelection'] == 'querystring_any') {
            if (array_key_exists($this->pageAttribute->getAttributeKeyID(), $this->querystringAttributes)) {
                if (isset($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()]) && !empty($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()])) {
                    if (!is_array($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()])) {
                        $this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()] = [$this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()]];
                    }
                    $clauseParts = [];
                    foreach ($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()] as $attributeKeyElement) {
                        $test_date = date('Y/m/d', strtotime($attributeKeyElement));
                        $clausePart = "(" . $this->currentAttributeFilter['handle'] . " REGEXP '" . $test_date . "')";
                        $clauseParts[] = $clausePart;
                    }
                    $concat = $this->currentAttributeFilter['filterSelection'] == 'querystring_all' ? ' AND ' : ' OR ';
                    $this->filter(false, "(" . implode($concat, $clauseParts) . ")");
                }
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_querystring_all') {
            if (array_key_exists($this->pageAttribute->getAttributeKeyID(), $this->querystringAttributes)) {
                if (isset($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()]) && !empty($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()])) {
                    if (!is_array($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()])) {
                        $this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()] = [$this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()]];
                    }
                    $clauseParts = [];
                    foreach ($this->querystringAttributes[$this->pageAttribute->getAttributeKeyID()] as $attributeKeyElement) {
                        $test_date = date('Y/m/d', strtotime($attributeKeyElement));
                        $clausePart = "(" . $this->currentAttributeFilter['handle'] . " NOT REGEXP '" . $test_date . "')";
                        $clauseParts[] = $clausePart;
                    }
                    $this->filter(false, "(" . implode(' AND ', $clauseParts) . ")");
                }
            }
        }
    }
}
