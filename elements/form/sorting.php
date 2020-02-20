<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php if ($controller->getShowSorting()) { ?>
    <script>
        $(function () {
            var $userSorting = $("#sbs_plp_container-<?php echo $controller->getIdentifier(); ?> .userSorting");
            if ($userSorting.find('OPTION').size() < 2) {
                $userSorting.hide();
            }
            <?php if ($controller->submitOnChangeOfFilter) { ?>
            $('#sbs_plp_form_<?php echo $controller->getIdentifier();?> .userSorting').bind('change', function () {
                $('#sbs_plp_form_<?php echo $controller->getIdentifier();?>').trigger('submit');
            });
            <?php } ?>
        });
    </script>
    <?php 
    $orderBy = null;
    if (is_array($controller->orderBy) && $controller->orderBy[0] == 'user_select') {
        if (count($controller->userSort)>0 && isset($_GET['orderBy'])) {
            $orderBy = $_GET['orderBy'];
        }
    }
    ?>
    <div class="sbs_plp_searchSorting" id="sbs_plp_searchSorting_<?php echo $controller->getIdentifier(); ?>">
        <div class="sbs_plp_searchSort">
            <label
                style="<?php echo $controller->hideSortTitle ? 'display:none;' : ''; ?>"><?php echo t('Sort Results'); ?></label>
            <select name="orderBy" class="userSorting">
                <?php if (in_array('display', $controller->userSort)) { ?>
                    <option
                        value="display_asc" <?php if ($orderBy == 'display_asc' || empty($controller->orderBy)) { ?> selected <?php } ?>><?php echo t('in their display order'); ?></option>
                    <option
                        value="display_desc" <?php if ($orderBy == 'display_desc' || empty($controller->orderBy)) { ?> selected <?php } ?>><?php echo t('in reverse display order'); ?></option>
                <?php } ?>
                <?php if (in_array('chrono', $controller->userSort)) { ?>
                    <option
                        value="chrono_desc" <?php if ($orderBy == 'chrono_desc') { ?> selected <?php } ?>><?php echo t('with the most recent first'); ?></option>
                    <option
                        value="chrono_asc" <?php if ($orderBy == 'chrono_asc') { ?> selected <?php } ?>><?php echo t('with the earliest first'); ?></option>
                <?php } ?>
                <?php if (in_array('modified', $controller->userSort)) { ?>
                    <option
                        value="modified_desc" <?php if ($orderBy == 'modified_desc') { ?> selected <?php } ?>><?php echo t('with the most recently changed first'); ?></option>
                    <option
                        value="modified_asc" <?php if ($orderBy == 'modified_asc') { ?> selected <?php } ?>><?php echo t('with the most recently changed last'); ?></option>
                <?php } ?>
                <?php if (in_array('alpha', $controller->userSort)) { ?>
                    <option
                        value="alpha_asc" <?php if ($orderBy == 'alpha_asc') { ?> selected <?php } ?>><?php echo t('in alphabetical order'); ?></option>
                    <option
                        value="alpha_desc" <?php if ($orderBy == 'alpha_desc') { ?> selected <?php } ?>><?php echo t('in reverse alphabetical order'); ?></option>
                <?php } ?>
                <?php if ($controller->showSearchBox && in_array('relevance', $controller->userSort)) { ?>
                    <option
                        value="relevance_desc" <?php if ($orderBy == 'relevance_desc') { ?> selected <?php } ?>><?php echo t('by keyword relevance'); ?></option>
                    <option
                        value="relevance_asc" <?php if ($orderBy == 'relevance_asc') { ?> selected <?php } ?>><?php echo t('by reverse keyword relevance'); ?></option>
                <?php } ?>
                <?php if (in_array('random', $controller->userSort)) { ?>
                    <option
                        value="random" <?php if ($orderBy == 'random') { ?> selected <?php } ?>><?php echo t('in random order'); ?></option>
                <?php } ?>
                <?php  foreach ($controller->getPageAttributes() as $pageAttribute) { ?>
                    <?php 
                    if (in_array('handle_' . $pageAttribute->getAttributeKeyHandle(), $controller->userSort) && in_array($pageAttribute->getAttributeTypeHandle(), ['text', 'email', 'url', 'telephone', 'textarea', 'boolean', 'date_time', 'number', 'rating', 'select', 'image_file'])) {
                        ?>
                        <option
                            value="<?php echo $pageAttribute->getAttributeKeyHandle(); ?>_asc" <?php if ($orderBy == $pageAttribute->getAttributeKeyHandle() . '_asc') { ?> selected <?php } ?>><?php echo t('by ') . $pageAttribute->getAttributeKeyName() . t(' (' . $controller->sortingOptions[$pageAttribute->getAttributeTypeHandle()]['az'] . ')'); ?></option>
                        <option
                            value="<?php echo $pageAttribute->getAttributeKeyHandle(); ?>_desc" <?php if ($orderBy == $pageAttribute->getAttributeKeyHandle() . '_desc') { ?> selected <?php } ?>><?php echo t('by ') . $pageAttribute->getAttributeKeyName() . t(' (' . $controller->sortingOptions[$pageAttribute->getAttributeTypeHandle()]['za'] . ')'); ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
    </div>
<?php } ?>