<?php  defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;

?>

<style>
    .sbs_plp_hider {
        margin-bottom: 0 !important;
    }
</style>
<div class="checkbox">
    <label class="span8" style="text-align:left;">
        <input type="checkbox" name="useForSearch"
               value="1" <?php if ($controller->useForSearch == 1) { ?> checked <?php } ?> id="useForSearch"/>
        <?php echo t("Use for Search"); ?>
    </label>
</div>
<div class="clearfix sbs_plp_hider"
     style="margin-left:20px;<?php if ($controller->useForSearch != 1) { ?>display:none;<?php } ?>">
    <div class="clearfix">
        <div class="checkbox" style="text-align:left;">
            <label class="span6" style="text-align:left;">
                <input type="checkbox" name="showSearchForm"
                       value="1" <?php if ($controller->showSearchForm == 1) { ?> checked <?php } ?>
                       id="showSearchForm"/>
                <?php echo t("Show Search Form"); ?>
            </label>
        </div>
    </div>
    <div class="clearfix sbs_plp_hider"
         style="margin-left:20px;<?php if ($controller->showSearchForm != 1) { ?>display:none<?php } ?>">
        <div class="clearfix">
            <div class="checkbox" style="text-align:left;">
                <label class="span6" style="text-align:left;">
                    <input type="checkbox" name="showSearchBox"
                           value="1" <?php if ($controller->showSearchBox == 1) { ?> checked <?php } ?>
                           id="showSearchBox"/>
                    <?php echo t("Show Search Box"); ?>
                </label>
            </div>
        </div>
        <div class="clearfix sbs_plp_hider"
             style="margin-left:20px;<?php if ($controller->showSearchBox != 1) { ?>display:none;<?php } ?>">
            <div class="form-group">
                <label class="control-label" for="searchBoxButtonText"><?php echo t('Button Label') ?></label>
                <input type="text" name="searchBoxButtonText"
                       value="<?php echo $controller->searchBoxButtonText ? $controller->searchBoxButtonText : '' ?>"
                       maxlength="255" class="form-control" id="searchButtonLabel"
                       placeholder="{<?php echo t('empty'); ?>}">
            </div>
            <div class="form-group">
                <label class="control-label" for="searchBoxTargetURL"><?php echo t('Search Target URL') ?></label>
                <input type="text" name="searchBoxTargetURL"
                       value="<?php echo $controller->searchBoxTargetURL ? $controller->searchBoxTargetURL : '' ?>"
                       maxlength="255" class="form-control" id="searchBoxTargetURL"
                       placeholder="{<?php echo t('current page'); ?>}">
            </div>
        </div>
        <div class="clearfix">
            <div class="checkbox" style="text-align:left;">
                <label class="span6" style="text-align:left;">
                    <input type="checkbox" name="submitOnChangeOfFilter"
                           value="1" <?php if ($controller->submitOnChangeOfFilter == 1) { ?> checked <?php } ?>
                           id="submitOnChangeOfFilter"/>
                    <?php echo t("Submit form as soon as a filter is changed"); ?>
                </label>
            </div>
        </div>
        <div class="clearfix">
            <div class="checkbox" style="text-align:left;">
                <label class="span6" style="text-align:left;">
                    <input type="checkbox" name="submitViaAjax"
                           value="1" <?php if ($controller->submitViaAjax == 1) { ?> checked <?php } ?>
                           id="submitViaAjax"/>
                    <?php echo t("Submit form without reloading the page using Ajax"); ?>
                </label>
            </div>
        </div>
        <div class="clearfix">
            <div class="checkbox" style="text-align:left;">
                <label class="span6" style="text-align:left;">
                    <input type="checkbox" name="showSearchFilters"
                           value="1" <?php if ($controller->showSearchFilters == 1) { ?> checked <?php } ?>
                           id="showSearchFilters"/>
                    <?php echo t("Show Search Filters"); ?>
                </label>
            </div>
        </div>
        <div class="clearfix sbs_plp_hider"
             style="padding-left:20px;<?php if ($controller->showSearchFilters != 1) { ?>display:none;<?php } ?>">
            <div class="clearfix">
                <div class="checkbox" style="text-align:left;">
                    <label class="span6" style="text-align:left;">
                        <input type="checkbox" name="showSearchFilterTitles"
                               value="1" <?php if ($controller->showSearchFilterTitles == 1) { ?> checked <?php } ?>
                               id="hideSearchFilterTitles"/>
                        <?php echo t("Show the attribute name as a filter title"); ?>
                    </label>
                </div>
            </div>
            <div class="clearfix sbs_plp_hider"
                 style="padding-left:20px;<?php if ($controller->showSearchFilterTitles != 1) { ?>display:none;<?php } ?>">
                <div class="clearfix">
                    <div class="checkbox" style="text-align:left;">
                        <label class="span6" style="text-align:left;">
                            <input type="checkbox" name="nameAsSearchFilterAllText"
                                   value="1" <?php if ($controller->nameAsSearchFilterAllText == 1) { ?> checked <?php } ?>
                                   id="nameAsSearchFilterAllText"/>
                            <?php echo t("Show the attribute name as the 'empty' filter option instead of a title"); ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="clearfix">
                <div class="checkbox">
                    <label class="span8" style="text-align:left;">
                        <input id="ccm-pagelist-searchFilterAllText-on" name="" type="checkbox"
                               value="1" <?php echo($controller->searchFilterAllText ? "checked=\"checked\"" : ""); ?>>
                        <?php echo t("Search filter 'all' text:"); ?>
                        <input
                            id="ccm-pagelist-searchFilterAllText" <?php echo($controller->searchFilterAllText ? "" : "disabled=\"disabled\""); ?>
                            type="text" name="searchFilterAllText"
                            value="<?php echo $controller->searchFilterAllText ? $controller->searchFilterAllText : ''; ?>"
                            class="span1">
                    </label>
                </div>
            </div>
            <div class="clearfix">
                <div class="checkbox" style="text-align:left;">
                    <label class="span6" style="text-align:left;">
                        <input type="checkbox" name="alphabetizeSearchSelects"
                               value="1" <?php if ($controller->alphabetizeSearchSelects == 1) { ?> checked <?php } ?>
                               id="alphabetizeSearchSelects"/>
                        <?php echo t("Show select and checkbox attribute values in alphabetical order"); ?>
                    </label>
                </div>
            </div>
            <div class="clearfix">
                <div class="checkbox" style="text-align:left;">
                    <label class="span6" style="text-align:left;">
                        <input type="checkbox" name="showSearchSelectsAsCheckbox"
                               value="1" <?php if ($controller->showSearchSelectsAsCheckbox == 1) { ?> checked <?php } ?>
                               id="showSearchSelectsAsCheckbox"/>
                        <?php echo t("Show select attributes as checkboxes"); ?>
                    </label>
                </div>
            </div>
            <div class="clearfix sbs_plp_hider"
                 style="padding-left:20px;<?php if ($controller->showSearchSelectsAsCheckbox != 1) { ?>display:none;<?php } ?>">
                <select multiple name="showSearchSelectAsCheckboxAttributes[]">
                    <?php 
                    if (count($controller->pageAttributes) > 0) :
                        foreach ($controller->pageAttributes as $pageAttribute) :
                            if (!array_key_exists($pageAttribute->getAttributeKeyHandle(), PageListPlus::getPageAttributeBlacklist())) :
                                if (in_array($pageAttribute->getAttributeTypeHandle(), PageListPlus::getSupportedAttributeTypes())) :
                                    ?>
                                    <option
                                        value="<?php echo $pageAttribute->getAttributeKeyID(); ?>" <?php if (in_array($pageAttribute->getAttributeKeyID(), $controller->showSearchSelectAsCheckboxAttributes)) { ?> selected <?php } ?>><?php echo $pageAttribute->getAttributeKeyName(); ?></option>
                                <?php 
                                endif;
                            endif;
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>


            <fieldset id="">
                <h4><?php echo t('Page Attributes') ?></h4>

                <div class="sortableAttributes" style="margin-left:20px;">
                    <?php 
                    // print out selected attributes
                    $pageAttributesUsedForFilters = [];
                    if (count($controller->pageAttributeIdsUsedInSearch) > 0) :
                        foreach ($controller->pageAttributeIdsUsedInSearch as $pageAttributeIdUsedInSearch) :
                            if (!intval($pageAttributeIdUsedInSearch)) {
                                if (!array_key_exists($pageAttributeIdUsedInSearch, $controller->pageAttributeBlacklist)) :
                                    $pageAttributesUsedForFilters[] = $pageAttributeIdUsedInSearch;
                                endif;
                            } else {
                                foreach ($controller->pageAttributes as $pageAttribute) :
                                    if ($pageAttribute->getAttributeKeyID() == $pageAttributeIdUsedInSearch) :
                                        if (in_array($pageAttribute->getAttributeTypeHandle(), $controller->supportedAttributeTypes)) :
                                            if (!array_key_exists($pageAttribute->getAttributeKeyHandle(), $controller->pageAttributeBlacklist)) :
                                                $pageAttributesUsedForFilters[] = $pageAttribute;
                                            endif;
                                        endif;
                                    endif;
                                endforeach;
                            }
                        endforeach;
                    endif;
                    if (count($pageAttributesUsedForFilters) > 0) :
                        foreach ($pageAttributesUsedForFilters as $pageAttribute) :
                            if (is_object($pageAttribute)) {
                                $keyId = $pageAttribute->getAttributeKeyID();
                                $keyName = $pageAttribute->getAttributeKeyName();
                            } else {
                                $keyId = $pageAttribute;
                                if ($pageAttribute=='cDatePublic') {
                                    $keyName = t('Public Date/Time');
                                } elseif ($pageAttribute=='cvDateModified') {
                                    $keyName = t('Date/Time Last Modified');
                                }
                            }
                            ?>
                            <div class="checkbox">
                                <label
                                    for="searchFilterId_<?php echo $keyId; ?>">
                                    <input type="checkbox" name="pageAttributeIdsUsedInSearch[]"
                                           value="<?php echo $keyId; ?>" <?php if (in_array($keyId, $controller->pageAttributeIdsUsedInSearch)) { ?> checked <?php } ?>
                                           id="searchFilterId_<?php echo $keyId; ?>"
                                           class="searchDefault"
                                           plp-attribute-id="<?php echo $keyId; ?>"/>
                                    <?php echo $keyName; ?>
                                </label>
                            </div>
                            <?php 
                        endforeach;
                    endif;
                    ?>
                    <hr>
                    <?php if (!in_array('cDatePublic', $pageAttributesUsedForFilters)) { ?>
                        <div class="checkbox">
                            <label for="sdID_cDatePublic">
                                <input type="checkbox" name="pageAttributeIdsUsedInSearch[]"
                                       value="cDatePublic" <?php if (in_array('cDatePublic', $controller->pageAttributeIdsUsedInSearch)) { ?> checked <?php } ?>
                                       id="sdID_cDatePublic" class="searchDefault" plp-attribute-id="cDatePublic"/>
                                <?php echo t('Public Date/Time') ?>
                            </label>
                        </div>
                    <?php } ?>
                    <?php if (!in_array('cvDateModified', $pageAttributesUsedForFilters)) { ?>
                        <div class="checkbox">
                            <label for="sdID_cvDateModified">
                                <input type="checkbox" name="pageAttributeIdsUsedInSearch[]"
                                       value="cvDateModified" <?php if (in_array('cvDateModified', $controller->pageAttributeIdsUsedInSearch)) { ?> checked <?php } ?>
                                       id="sdID_cvDateModified" class="searchDefault" plp-attribute-id="cvDateModified"/>
                                <?php echo t('Date/Time Last Modified') ?>
                            </label>
                        </div>
                    <?php } ?>

                    <?php 
                    // print out unselected attributes
                    foreach ($controller->pageAttributes as $pageAttribute) :
                        if (!in_array($pageAttribute->getAttributeKeyID(), $controller->pageAttributeIdsUsedInSearch)) :
                            if (!array_key_exists($pageAttribute->getAttributeKeyHandle(), $controller->pageAttributeBlacklist)) :
                                if (in_array($pageAttribute->getAttributeTypeHandle(), $controller->supportedAttributeTypes)) :
                                    if (array_key_exists($pageAttribute->getAttributeKeyID(), $controller->searchDefaults)) :
                                        $isSelected = true;
                                        $val = $controller->searchDefaults[$pageAttribute->getAttributeKeyID()];
                                    else :
                                        $isSelected = false;
                                        $val = '';
                                    endif;
                                    ?>
                                    <div class="checkbox">
                                        <label for="searchFilterId_<?php echo $pageAttribute->getAttributeKeyID(); ?>">
                                            <input type="checkbox" name="pageAttributeIdsUsedInSearch[]"
                                                   value="<?php echo $pageAttribute->getAttributeKeyID(); ?>" <?php if (in_array($pageAttribute->getAttributeKeyID(), $controller->pageAttributeIdsUsedInSearch)) { ?> checked <?php } ?>
                                                   id="searchFilterId_<?php echo $pageAttribute->getAttributeKeyID(); ?>"
                                                   class="searchDefault"
                                                   plp-attribute-id="<?php echo $pageAttribute->getAttributeKeyID(); ?>"/>
                                            <?php echo $pageAttribute->getAttributeKeyName(); ?>
                                        </label>
                                    </div>
                                <?php 
                                endif;
                            endif;
                        endif;
                    endforeach;
                    ?>
                </div>
            </fieldset>
        </div>
    </div>
    <div class="clearfix">
        <div class="checkbox" style="text-align:left;">
            <label class="span6" style="text-align:left;">
                <input type="checkbox" name="showSearchResults"
                       value="1" <?php if ($controller->showSearchResults == 1) { ?> checked <?php } ?>
                       id="showSearchResults"/>
                <?php echo t("Show Results"); ?>
            </label>
        </div>
    </div>
    <div class="clearfix sbs_plp_hider"
         style="margin-left:20px;<?php if ($controller->showSearchResults != 1) { ?>display:none;<?php } ?>">
        <div class="clearfix">
            <div class="checkbox" style="text-align:left;">
                <label class="span6" style="text-align:left;">
                    <input type="checkbox" name="showAllResultsOnLoad"
                           value="1" <?php if ($controller->showAllResultsOnLoad == 1) { ?> checked <?php } ?>
                           id="showAllResultsOnLoad"/>
                    <?php echo t("Show all pages in results on page load"); ?>
                </label>
            </div>
        </div>
        <div class="clearfix">
            <div class="checkbox" style="text-align:left;">
                <label class="span6" style="text-align:left;">
                    <input type="checkbox" name="receiveViaAjax"
                           value="1" <?php if ($controller->receiveViaAjax == 1) { ?> checked <?php } ?>
                           id="receiveViaAjax"/>
                    <?php echo t("Get results for Ajax form submissions"); ?>
                </label>
            </div>
        </div>
    </div>

</div>