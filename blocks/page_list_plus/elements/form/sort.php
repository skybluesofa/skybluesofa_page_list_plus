<?php  defined('C5_EXECUTE') or die("Access Denied.");
$supportedAttributeTypes = $controller->supportedAttributeTypes;

foreach ($controller->orderBy as $key => $orderBy) {
    $label = ($key == 0) ? t('First sort pages') : t('Then sort pages');
    ?>
    <div class="clearfix">
        <label><?php echo $label; ?></label>

        <div class="input">
            <select name="orderBy[]" id="orderBy<?php echo $key; ?>" class="form-control">
                <?php if ($key == 0) { ?>
                    <option
                        value="user_select" <?php if ($orderBy == 'user_select') { ?> selected <?php } ?>><?php echo t('in the order selected by the user'); ?></option>
                <?php } ?>
                <option
                    value="display_asc" <?php if ($orderBy == 'display_asc' || empty($orderBy)) { ?> selected <?php } ?>><?php echo t('in their sitemap order'); ?></option>
                <option
                    value="chrono_desc" <?php if ($orderBy == 'chrono_desc') { ?> selected <?php } ?>><?php echo t('with the most recent first'); ?></option>
                <option
                    value="chrono_asc" <?php if ($orderBy == 'chrono_asc') { ?> selected <?php } ?>><?php echo t('with the earliest first'); ?></option>
                <option
                    value="modified_desc" <?php if ($orderBy == 'modified_desc') { ?> selected <?php } ?>><?php echo t('with the most recently changed first'); ?></option>
                <option
                    value="modified_asc" <?php if ($orderBy == 'modified_asc') { ?> selected <?php } ?>><?php echo t('with the most recently changed last'); ?></option>
                <option
                    value="alpha_asc" <?php if ($orderBy == 'alpha_asc') { ?> selected <?php } ?>><?php echo t('in alphabetical order'); ?></option>
                <option
                    value="alpha_desc" <?php if ($orderBy == 'alpha_desc') { ?> selected <?php } ?>><?php echo t('in reverse alphabetical order'); ?></option>
                <option
                    value="relevance_desc" <?php if ($orderBy == 'relevance_desc') { ?> selected <?php } ?>><?php echo t('by keyword relevance'); ?></option>
                <option
                    value="relevance_asc" <?php if ($orderBy == 'relevance_asc') { ?> selected <?php } ?>><?php echo t('by reverse keyword relevance'); ?></option>
                <option
                    value="random" <?php if ($orderBy == 'random') { ?> selected <?php } ?>><?php echo t('in random order'); ?></option>
                <?php 
                foreach ($controller->pageAttributes as $pageAttribute) :
                    if (!array_key_exists($pageAttribute->getAttributeKeyHandle(), $controller->pageAttributeBlacklist) && in_array($pageAttribute->getAttributeTypeHandle(), $supportedAttributeTypes)) :
                        ?>
                        <option
                            value="<?php echo $pageAttribute->getAttributeKeyHandle(); ?>_asc" <?php if ($orderBy == $pageAttribute->getAttributeKeyHandle() . '_asc') { ?> selected <?php } ?>><?php echo t('by ') . $pageAttribute->getAttributeKeyName() . t(' (' . $controller->sortingOptions[$pageAttribute->getAttributeTypeHandle()]['az'] . ')'); ?></option>
                        <option
                            value="<?php echo $pageAttribute->getAttributeKeyHandle(); ?>_desc" <?php if ($orderBy == $pageAttribute->getAttributeKeyHandle() . '_desc') { ?> selected <?php } ?>><?php echo t('by ') . $pageAttribute->getAttributeKeyName() . t(' (' . $controller->sortingOptions[$pageAttribute->getAttributeTypeHandle()]['za'] . ')'); ?></option>
                    <?php 
                    endif;
                endforeach;
                ?>
            </select>
        </div>
    </div>
    <?php if ($key == 0) { ?>
        <div
            id="userSortableTable" <?php if ($orderBy != 'user_select') echo 'style="margin-left:20px;display:none;"'; ?>>
            <div class="clearfix">
                <div class="checkbox">
                    <label class="span6">
                        <input type="checkbox" name="hideSortTitle"
                               value="1" <?php if ($controller->hideSortTitle == 1) { ?> checked <?php } ?>
                               id="hideSortTitle"/>
                        <?php echo t("Hide sortbox field title"); ?>
                    </label>
                </div>
            </div>

            <fieldset id="">
                <h4><?php echo t('Allow Users to Sort By') ?></h4>

                <div style="width:49%;float:left;">
                    <h5><?php echo t('Standard Properties'); ?></h5>

                    <div style="margin-left:20px;">
                        <div class="checkbox">
                            <label for="checkbox_us_display" class="span5">
                                <input class="usCheckbox" type="checkbox" name="userSort[]" value="display"
                                       id="checkbox_us_display" <?php echo in_array('display', $controller->userSort) ? 'checked="checked"' : ''; ?>>
                                <?php echo t("Display Order"); ?>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label for="checkbox_us_chronological" class="span5">
                                <input class="usCheckbox" type="checkbox" name="userSort[]" value="chrono"
                                       id="checkbox_us_chronological" <?php echo in_array('chrono', $controller->userSort) ? 'checked="checked"' : ''; ?>>
                                <?php echo t("Chronological Order"); ?>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label for="checkbox_us_modification" class="span5">
                                <input class="usCheckbox" type="checkbox" name="userSort[]" value="modified"
                                       id="checkbox_us_modification" <?php echo in_array('modified', $controller->userSort) ? 'checked="checked"' : ''; ?>>
                                <?php echo t("Modification Date"); ?>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label for="checkbox_us_alphabetical" class="span5">
                                <input class="usCheckbox" type="checkbox" name="userSort[]" value="alpha"
                                       id="checkbox_us_alphabetical" <?php echo in_array('alpha', $controller->userSort) ? 'checked="checked"' : ''; ?>>
                                <?php echo t("Alphabetical Order"); ?>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label for="checkbox_us_relevance" class="span5">
                                <input class="usCheckbox" type="checkbox" name="userSort[]" value="relevance"
                                       id="checkbox_us_relevance" <?php echo in_array('relevance', $controller->userSort) ? 'checked="checked"' : ''; ?>>
                                <?php echo t("Keyword Relevance"); ?>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label for="checkbox_us_random" class="span5">
                                <input class="usCheckbox" type="checkbox" name="userSort[]" value="random"
                                       id="checkbox_us_random" <?php echo in_array('random', $controller->userSort) ? 'checked="checked"' : ''; ?>>
                                <?php echo t("Random Order"); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div style="width:50%;float:right;">
                    <h5><?php echo t('Custom Properties'); ?></h5>

                    <div style="margin-left:20px;">
                        <?php 
                        foreach ($controller->pageAttributes as $pageAttribute) :
                            if (!array_key_exists($pageAttribute->getAttributeKeyHandle(), $controller->pageAttributeBlacklist) && in_array($pageAttribute->getAttributeTypeHandle(), $supportedAttributeTypes)) :
                                ?>
                                <div class="checkbox">
                                    <label
                                        for="checkbox_us_handle_<?php echo $pageAttribute->getAttributeKeyHandle(); ?>"
                                        class="span5">
                                        <input class="usCheckbox" type="checkbox" name="userSort[]"
                                               value="handle_<?php echo $pageAttribute->getAttributeKeyHandle(); ?>"
                                               id="checkbox_us_handle_<?php echo $pageAttribute->getAttributeKeyHandle(); ?>"
                                            <?php echo in_array('handle_' . $pageAttribute->getAttributeKeyHandle(), $controller->userSort) ? 'checked="checked"' : ''; ?>>
                                        <?php echo $pageAttribute->getAttributeKeyName(); ?>
                                    </label>
                                </div>
                            <?php 
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
            </fieldset>
        </div>
    <?php 
    }
}
