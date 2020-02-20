<?php  defined('C5_EXECUTE') or die("Access Denied.");

?>

        <h4><?php echo t('Basic Filter Setup') ?></h4>
        <div class="checkbox">
            <label class="checkbox span3">
                <input type="checkbox" id="filter_hideCurrentPage" style="margin-left:-20px;"
                       name="hideCurrentPage"
                       value="1" <?php if ($controller->hideCurrentPage == 1) { ?> checked <?php } ?> />
                <?php echo t("Don't show current page in results."); ?>
            </label>
        </div>
        <div class="form-group">
            <label class="control-label"
                   for="plp_resultsRelatedTo"><?php echo t('Results relate to') ?></label>

            <select name="resultsRelatedTo" id="plp_resultsRelatedTo" class="form-control">
                <option></option>
                <option
                    value="search"<?php if ($controller->resultsRelatedTo=='search') print 'selected'; ?>>
                    <?php echo t('Search Value'); ?>
                </option>
                <option
                    value="keywords"<?php if ($controller->keyword) print 'selected'; ?>>
                    <?php echo t('Specific keywords'); ?>
                </option>
                <option
                    value="area"<?php if ($controller->relatedToArea) print 'selected'; ?>>
                    <?php echo t('An area on the current page'); ?>
                </option>
            </select>
        </div>
        <div style="margin-left:21px;" id="results_relate_to">
            <div class="form-group related_to_control" id="related_to_keywords">
                <input type="text" name="keyword" value="<?php echo $controller->keyword ?>" class="form-control"
                       id="plp_keywords">
            </div>
            <div class="form-group related_to_control" id="related_to_area">
                <select name="relatedToArea" id="plp_area" class="form-control">
                    <option></option>
                    <option
                        value="-!-all areas-!-"<?php if ($controller->relatedToArea == '-!-all areas-!-') print 'selected'; ?>>
                        - <?php echo t('Entire Page'); ?> -
                    </option>
                    <?php  foreach ($controller->areas as $area) : ?>
                        <option
                            value="<?php echo $area; ?>" <?php if ($controller->relatedToArea == $area) print 'selected'; ?>><?php echo $area; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="checkbox">
                <label class="checkbox span3">
                    <input type="checkbox" id="filter_useFulltextSearch" style="margin-left:-20px;"
                           name="useFulltextSearch"
                           value="1" <?php if ($controller->useFulltextSearch == 1) { ?> checked <?php } ?> />
                    <?php 
                    echo t("Use MySQL fulltext search.");
                    ?>
                </label>
            </div>
            <div class="sbs_plp_hider" id="filter_useFulltextSearch_options"
                 style="padding-left:20px;<?php if ($controller->useFulltextSearch != 1) { ?>display:none;<?php } ?>">
                <select name="mysqlFulltextModifier" class="form-control">
                    <option value="natural"><?php echo t('Natural Language (Default)'); ?></option>
                    <option
                        value="expand" <?php if ($controller->mysqlFulltextModifier == 'expand') print 'selected'; ?>><?php echo t('Expand on first result to find additional relevant results (Query Expansion)'); ?></option>
                    <option value="natural-expand"><?php echo t('Natural Language & Query Expansion'); ?></option>
                    <option
                        value="phrase" <?php if ($controller->mysqlFulltextModifier == 'phrase') print 'selected'; ?>><?php echo t('Use all the keywords as a Single Phrase (Boolean Mode, Quoted)'); ?></option>
                    <option
                        value="boolean" <?php if ($controller->mysqlFulltextModifier == 'boolean') print 'selected'; ?>><?php echo t('Individual words with (+/-) modifiers (Basic Boolean Mode)'); ?></option>
                </select>
            </div>
        </div>

        <h4><?php echo t('Standard Properties') ?></h4>
        <table style="width:100%;">
            <?php 
            $standardProperties = [
                'cID' => t('Page ID'),
                'cvName' => t('Page Name'),
                'cvDescription' => t('Page Description'),
                'cvDatePublic' => t('Date made Public'),
                'cDateModified' => t('Date Last Modified'),
                'uID' => t('Author'),
                'cvApproverUID' => t('Approved By')
            ];
            foreach ($standardProperties as $standardPropertyHandle => $standardPropertyName) {
                $isSelected = false;
                $filterSelection = '';
                $val1 = '';
                $val2 = '';
                if (array_key_exists($standardPropertyHandle, $controller->pageAttributesUsedForFilter)) :
                    $isSelected = true;
                    $filterSelection = $controller->pageAttributesUsedForFilter[$standardPropertyHandle]['filterSelection'];
                    $val1 = $controller->pageAttributesUsedForFilter[$standardPropertyHandle]['val1'];
                    $val2 = $controller->pageAttributesUsedForFilter[$standardPropertyHandle]['val2'];
                endif;
                $elementValues = [
                    'controller' => $controller,
                    'filterSelection' => $filterSelection,
                    'pageAttributeKeyID' => $standardPropertyHandle,
                    'pageAttribute' => null,
                    'values' => [$val1, $val2],
                    'disallowSearch' => true
                ];
                ?>
                <tr>
                    <?php 
                    ?>
                    <td>
                        <div class="checkbox"><label for="checkbox_pageAttribute_<?php echo $standardPropertyHandle; ?>"
                                                     class="span2">
                                <input class="pageAttributeCheckbox filterAttribute" style="margin-left:-20px;"
                                       type="checkbox" name="pageAttributeId[]"
                                       value="<?php echo $standardPropertyHandle; ?>"
                                       id="checkbox_pageAttribute_<?php echo $standardPropertyHandle; ?>" <?php echo $isSelected ? 'checked' : ''; ?>> <?php echo $standardPropertyName; ?>
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="pageAttributeFilterSelection"
                             style="<?php echo $isSelected ? '' : 'display:none;'; ?>white-space:nowrap;">
                            <?php 
                            if (in_array($standardPropertyHandle, ['cvName', 'cvDescription'])) {
                                View::element('blocks/page_list_plus/form/filters/text', $elementValues, 'skybluesofa_page_list_plus');
                                //$this->inc('elements/form/filters/text.php', $elementValues);
                            } elseif (in_array($standardPropertyHandle, ['cID'])) {
                                View::element('blocks/page_list_plus/form/filters/number', $elementValues, 'skybluesofa_page_list_plus');
                                //$this->inc('elements/form/filters/number.php', $elementValues);
                            } elseif (in_array($standardPropertyHandle, ['cvDatePublic', 'cDateModified'])) {
                                View::element('blocks/page_list_plus/form/filters/date_time', $elementValues, 'skybluesofa_page_list_plus');
                                //$this->inc('elements/form/filters/date_time.php', $elementValues);
                            } elseif (in_array($standardPropertyHandle, ['uID','cvAuthorUID','cvApproverUID'])) {
                                View::element('blocks/page_list_plus/form/filters/user', $elementValues, 'skybluesofa_page_list_plus');
                                //$this->inc('elements/form/filters/user.php', $elementValues);
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            <?php 
            }
            ?>
        </table>

<?php 
if (is_array($controller->pageAttributes)) :
    ?>
        <h4><?php echo t('Custom Page Attributes') ?></h4>
        <table style="width:100%;">
            <?php 
            foreach ($controller->pageAttributes as $pageAttribute) :
                if (!array_key_exists($pageAttribute->getAttributeKeyHandle(), $controller->pageAttributeBlacklist)) :
                    if (in_array($pageAttribute->getAttributeTypeHandle(), $controller->supportedAttributeTypes)) :
                        $isSelected = false;
                        $filterSelection = '';
                        $val1 = '';
                        $val2 = '';
                        $val3 = '';
                        $val4 = '';
                        if (array_key_exists($pageAttribute->getAttributeKeyID(), $controller->pageAttributesUsedForFilter)) :
                            $isSelected = true;
                            $filterSelection = $controller->pageAttributesUsedForFilter[$pageAttribute->getAttributeKeyID()]['filterSelection'];
                            $val1 = $controller->pageAttributesUsedForFilter[$pageAttribute->getAttributeKeyID()]['val1'];
                            $val2 = $controller->pageAttributesUsedForFilter[$pageAttribute->getAttributeKeyID()]['val2'];
                            if (array_key_exists($pageAttribute->getAttributeKeyID(), $controller->searchDefaults)) :
                                $val3 = $controller->searchDefaults[$pageAttribute->getAttributeKeyID()];
                            endif;
                        endif;
                        $elementValues = [
                            'controller' => $controller,
                            'filterSelection' => $filterSelection,
                            'pageAttributeKeyID' => $pageAttribute->getAttributeKeyID(),
                            'pageAttribute' => $pageAttribute,
                            'values' => [$val1, $val2, $val3, $val4]
                        ];
                        ?>
                        <tr>
                            <td>
                                <div class="checkbox">
                                    <label
                                        for="checkbox_pageAttribute_<?php echo $pageAttribute->getAttributeKeyID(); ?>"
                                        class="span2">
                                        <input style="margin-left:-20px;" class="pageAttributeCheckbox filterAttribute"
                                               plp-attribute-id="<?php echo $pageAttribute->getAttributeKeyID(); ?>"
                                               type="checkbox" name="pageAttributeId[]"
                                               value="<?php echo $pageAttribute->getAttributeKeyID(); ?>"
                                               id="checkbox_pageAttribute_<?php echo $pageAttribute->getAttributeKeyID(); ?>" <?php echo $isSelected ? 'checked' : ''; ?>>
                                        <?php echo $pageAttribute->getAttributeKeyName(); ?>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="pageAttributeFilterSelection"
                                     style="<?php echo $isSelected ? '' : 'display:none;'; ?>white-space:nowrap;">
                                    <?php 
                                    $attributeFilterHtml = $controller->getAttributeFormFilterView($pageAttribute, $elementValues);
                                    if ($attributeFilterHtml) {
                                        echo $attributeFilterHtml;
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php 
                    endif;
                endif;
            endforeach;
            ?>
        </table>
<?php 
endif;
