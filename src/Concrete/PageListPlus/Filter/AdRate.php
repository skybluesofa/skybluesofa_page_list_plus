<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter;

use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Filter;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;

defined('C5_EXECUTE') or die("Access Denied.");

class AdRate extends Contract\FilterContract
{
    public function run()
    {
        if ($this->currentAttributeFilter['filterSelection'] == "is_empty") {
            $this->pageListPlus->filterByClause("/* Ad Rate Filter */ (" . $this->currentAttributeFilter['handle'] . "_percentage IS NULL OR " . $this->currentAttributeFilter['handle'] . "_percentage<1)");
        } else {
            $this->pageListPlus->filterByClause("/* Ad Rate Filter */ (" . $this->currentAttributeFilter['handle'] . "_percentage>=1)");
        }
    }
}
