<?php  defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Attribute\Key\CollectionKey;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;

if ($controller->getShowSearchForm() && $controller->showSearchFilters && $controller->pageAttributesUsedForFilter) {
    $searchFilters = $controller->getSearchFilters();
    ?>
    <div class="sbs_plp_searchFilterClear"></div>
    <?php if (count($searchFilters)) { ?>
        <div class="sbs_plp_searchFilters" id="sbs_plp_searchFilters_<?php echo $controller->getIdentifier(); ?>">
            <?php  foreach ($searchFilters as $searchFilter) { ?>
                <div class="sbs_plp_searchFilter">
                    <label
                        style="<?php echo ($controller->showSearchFilterTitles && !$controller->nameAsSearchFilterAllText) ? '' : 'display:none;'; ?>"><?php echo $searchFilter->getAttributeKeyName(); ?></label>
                    <?php 
                    $attributeSearchHtml = $controller->getAttributeFormSearchView($searchFilter);
                    if ($attributeSearchHtml) {
                        echo $attributeSearchHtml;
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
<?php }