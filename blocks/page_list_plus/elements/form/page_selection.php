<?php  defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Page\Type\Type as PageType;
use Concrete\Core\Page\Template as PageTemplate;
use Concrete\Core\Page\Theme\Theme as PageTheme;
use Concrete\Core\Form\Service\Widget\PageSelector;

?>
    <h4><?php echo t('Types of Pages') ?></h4>
    <table style="width:100%;margin-bottom:0;" class="table table-bordered">
        <tr>
            <td colspan="3">
                <div class="checkbox">
                    <input type="checkbox" style="margin-left:6px;" name="pageTypeId[]" value="-1"
                           id="checkbox_pageType_all" <?php echo (!$controller->pageTypeId || count($controller->pageTypeId) == 0) ? 'checked' : ''; ?>>
                    <label for="checkbox_pageType_all" class="checkbox" style="text-align:left;margin:0 0 0 7px;padding-top:1px;">
                        <?php 
                        echo t('All Page Types');
                        ?>
                    </label>
                </div>
        </tr>
        <?php 
        $pageTypes = PageType::getList();
        if (is_array($pageTypes)) :
            $pageTypeCount = count($pageTypes);
            if ($pageTypeCount == 1) :
                $columns = 1;
            elseif (in_array($pageTypeCount, [2, 4])) :
                $columns = 2;
            else :
                $columns = 3;
            endif;
            $pageTypesPerColumn = ceil(count($pageTypes) / $columns);
            for ($i = 0; $i < $columns; $i++) :
                $pageTypeCols[$i] = array_slice($pageTypes, ($pageTypesPerColumn * $i), $pageTypesPerColumn);
            endfor;
            $rowVisibility = '';
            if (!count($controller->pageTypeId)) {
                $rowVisibility = 'none';
            } elseif (count($controller->pageTypeId) == 1 && $controller->pageTypeId[0] == 0) {
                $rowVisibility = 'none';
            }
            for ($row = 1; $row <= $pageTypesPerColumn; $row++) :
                ?>
                <tr class="row_pageType_additional" style="display:<?php echo $rowVisibility; ?>">
                    <?php 
                    foreach ($pageTypeCols as $key => $pageTypes) :
                        $pageType = array_shift($pageTypeCols[$key]);
                        ?>
                        <td style="width:<?php echo floor(100 / $columns); ?>%;vertical-align:top;">
                            <?php 
                            if (is_object($pageType)) :
                                ?>
                                <div class="checkbox">
                                    <input type="checkbox" style="margin-left:6px;" name="pageTypeId[]"
                                           value="<?php echo $pageType->getPageTypeID(); ?>" <?php echo(in_array($pageType->getPageTypeID(), $controller->pageTypeId) ? 'checked' : ''); ?>
                                           id="checkbox_pageType_<?php echo $pageType->getPageTypeID(); ?>"
                                           class="checkbox_pageType">
                                    <label for="checkbox_pageType_<?php echo $pageType->getPageTypeID(); ?>"
                                           class="checkbox" style="text-align:left;margin:0 0 0 7px;padding-top:1px;">
                                        <?php 
                                        echo $pageType->getPageTypeName();
                                        ?>
                                    </label>
                                </div>
                            <?php 
                            endif;
                            ?>
                        </td>
                    <?php 
                    endforeach;
                    ?>
                </tr>
            <?php 
            endfor;
            ?>
        <?php 
        endif;
        ?>
    </table>

    <h4><?php echo t('Page Templates') ?></h4>
    <table style="width:100%;margin-bottom:0;" class="table table-bordered">
        <tr>
            <td colspan="3">
                <div class="checkbox">
                    <input type="checkbox" style="margin-left:6px;" name="pageTemplateId[]" value="-1"
                           id="checkbox_pageTemplate_all" <?php echo (!$controller->pageTemlateId || count($controller->pageTemplateId) == 0) ? 'checked' : ''; ?>>
                    <label for="checkbox_pageTemplate_all" class="checkbox" style="text-align:left;margin:0 0 0 7px;padding-top:1px;">
                        <?php 
                        echo t('All Page Templates');
                        ?>
                    </label>
                </div>
        </tr>
        <?php 
        $pageTemplates = PageTemplate::getList();
        if (is_array($pageTemplates)) :
            $templateCount = count($pageTemplates);
            if ($templateCount == 1) :
                $columns = 1;
            elseif (in_array($templateCount, [2, 4])) :
                $columns = 2;
            else :
                $columns = 3;
            endif;
            $templatesPerColumn = ceil(count($pageTemplates) / $columns);
            for ($i = 0; $i < $columns; $i++) :
                $pageTemplateCols[$i] = array_slice($pageTemplates, ($templatesPerColumn * $i), $templatesPerColumn);
            endfor;
            $rowVisibility = '';
            if (!count($controller->pageTemplateId)) {
                $rowVisibility = 'none';
            } elseif (count($controller->pageTemplateId) == 1 && $controller->pageTemplateId[0] == 0) {
                $rowVisibility = 'none';
            }
            for ($row = 1; $row <= $templatesPerColumn; $row++) :
                ?>
                <tr class="row_pageTemplate_additional" style="display:<?php echo $rowVisibility; ?>">
                    <?php 
                    foreach ($pageTemplateCols as $key => $pageTemplates) :
                        $pageTemplate = array_shift($pageTemplateCols[$key]);
                        ?>
                        <td style="width:<?php echo floor(100 / $columns); ?>%;vertical-align:top;">
                            <?php 
                            if (is_object($pageTemplate)) :
                                ?>
                                <div class="checkbox">
                                    <input type="checkbox" style="margin-left:6px;" name="pageTemplateId[]"
                                           value="<?php echo $pageTemplate->getPageTemplateID(); ?>" <?php echo(in_array($pageTemplate->getPageTemplateID(), $controller->pageTemplateId) ? 'checked' : ''); ?>
                                           id="checkbox_pageTemplate_<?php echo $pageTemplate->getPageTemplateID(); ?>"
                                           class="checkbox_pageTemplate">
                                    <label for="checkbox_pageTemplate_<?php echo $pageTemplate->getPageTemplateID(); ?>"
                                           class="checkbox" style="text-align:left;margin:0 0 0 7px;padding-top:1px;">
                                        <?php 
                                        echo $pageTemplate->getPageTemplateDisplayName('text');
                                        ?>
                                    </label>
                                </div>
                            <?php 
                            endif;
                            ?>
                        </td>
                    <?php 
                    endforeach;
                    ?>
                </tr>
            <?php 
            endfor;
            ?>
        <?php 
        endif;
        ?>
    </table>

    <h4><?php echo t('Page Themes') ?></h4>
    <table style="width:100%;margin-bottom:0;" class="table table-bordered">
        <tr>
            <td colspan="3">
                <div class="checkbox">
                    <input type="checkbox" style="margin-left:6px;" name="pageThemeId[]" value="-1"
                           id="checkbox_pageTheme_all" <?php echo (!$controller->pageThemeId || count($controller->pageThemeId) == 0) ? 'checked' : ''; ?>>
                    <label for="checkbox_pageTheme_all" class="checkbox" style="text-align:left;margin:0 0 0 7px;padding-top:1px;">
                        <?php 
                        echo t('All Page Themes');
                        ?>
                    </label>
                </div>
        </tr>
        <?php 
        $pageThemes = PageTheme::getList();
        if (is_array($pageThemes)) :
            $themeCount = count($pageThemes);
            if ($themeCount == 1) :
                $columns = 1;
            elseif (in_array($themeCount, [2, 4])) :
                $columns = 2;
            else :
                $columns = 3;
            endif;
            $themesPerColumn = ceil(count($pageThemes) / $columns);
            for ($i = 0; $i < $columns; $i++) :
                $pageThemeCols[$i] = array_slice($pageThemes, ($themesPerColumn * $i), $themesPerColumn);
            endfor;
            $rowVisibility = '';
            if (!count($controller->pageThemeId)) {
                $rowVisibility = 'none';
            } elseif (count($controller->pageThemeId) == 1 && $controller->pageThemeId[0] == 0) {
                $rowVisibility = 'none';
            }
            for ($row = 1; $row <= $themesPerColumn; $row++) :
                ?>
                <tr class="row_pageTheme_additional" style="display:<?php echo $rowVisibility; ?>">
                    <?php 
                    foreach ($pageThemeCols as $key => $pageThemes) :
                        $pageTheme = array_shift($pageThemeCols[$key]);
                        ?>
                        <td style="width:<?php echo floor(100 / $columns); ?>%;vertical-align:top;">
                            <?php 
                            if (is_object($pageTheme)) :
                                ?>
                                <div class="checkbox">
                                    <input type="checkbox" style="margin-left:6px;" name="pageThemeId[]"
                                           value="<?php echo $pageTheme->getThemeID(); ?>" <?php echo(in_array($pageTheme->getThemeID(), $controller->pageThemeId) ? 'checked' : ''); ?>
                                           id="checkbox_pageTheme_<?php echo $pageTheme->getThemeID(); ?>"
                                           class="checkbox_pageTheme">
                                    <label for="checkbox_pageTheme_<?php echo $pageTheme->getThemeID(); ?>"
                                           class="checkbox" style="text-align:left;margin:0 0 0 7px;padding-top:1px;">
                                        <?php 
                                        echo $pageTheme->getThemeDisplayName('text');
                                        ?>
                                    </label>
                                </div>
                            <?php 
                            endif;
                            ?>
                        </td>
                    <?php 
                    endforeach;
                    ?>
                </tr>
            <?php 
            endfor;
            ?>
        <?php 
        endif;
        ?>
    </table>



    <h4><?php echo t('Display pages that are located') ?></h4>
    <div class="form-group">
        <select name="parentPageId" id="parentPageId" class="form-control">
            <option
                value="EVERYWHERE" <?php echo ($controller->parentPageId == "EVERYWHERE") ? ' selected' : ''; ?>><?php echo t('everywhere'); ?></option>
            <option
                value="CURRENT_BRANCH" <?php echo ($controller->parentPageId == "CURRENT_BRANCH") ? ' selected' : ''; ?>><?php echo t('in the current branch'); ?></option>
            <option
                value="CURRENT_LEVEL" <?php echo ($controller->parentPageId == "CURRENT_LEVEL") ? ' selected' : ''; ?>><?php echo t('at the current level'); ?></option>
            <option
                value="PARENT_LEVEL" <?php echo ($controller->parentPageId == "PARENT_LEVEL") ? ' selected' : ''; ?>><?php echo t('at the parent level'); ?></option>
            <option
                value="BELOW_HERE" <?php echo ($controller->parentPageId == 'BELOW_HERE') ? ' selected' : ''; ?>><?php echo t('beneath this page'); ?></option>
            <option
                value="OTHER" <?php if (!empty($controller->parentPageId) && !in_array($controller->parentPageId, ["EVERYWHERE", "CURRENT_BRANCH", "PARENT_LEVEL", "CURRENT_LEVEL", "BELOW_HERE"])) { ?> selected<?php } ?>><?php echo t('beneath another page'); ?></option>
        </select>
    </div>
    <div class="ccm-page-list-page-other"
         style="<?php if (empty($controller->parentPageId) || in_array($controller->parentPageId, ["EVERYWHERE", "CURRENT_BRANCH", "PARENT_LEVEL", "CURRENT_LEVEL", "BELOW_HERE"])) { ?>display: none;<?php } ?>">
        <?php 
        $pageSelector = new PageSelector();
        print $pageSelector->selectPage('parentPageIdValue', !in_array($controller->parentPageId, ["EVERYWHERE", "CURRENT_BRANCH", "PARENT_LEVEL", "CURRENT_LEVEL", "BELOW_HERE"]) ? (int)$controller->parentPageIdValue : null);
        ?>
    </div>
    <div class="ccm-page-list-all-descendents"
         style="margin: 0 0 0 35px;<?php echo (!$controller->parentPageId || $controller->parentPageId == 'EVERYWHERE') ? ' display: none;' : ''; ?>">
        <div class="form-group">
            <div class="checkbox">
                <label for="includeAllDescendents" class="checkbox" style="text-align:left;margin-bottom:0;">
                    <input type="checkbox" style="margin-left:-20px;" name="includeAllDescendents"
                           value="1" <?php echo $controller->includeAllDescendents ? 'checked="checked"' : '' ?>
                           id="includeAllDescendents">
                    <?php echo t('Include all child pages'); ?>
                </label>
            </div>
        </div>
    </div>



    <h4><?php echo t('Viewing Permissions') ?></h4>
    <div class="checkbox">
        <label>
            <input type="checkbox" style="margin-left:-20px;" name="ignorePermissions"
                   value="1" <?php if ($controller->ignorePermissions == 1) { ?> checked <?php } ?> />
            <?php 
            echo t('Display pages to users even when those users cannot access those pages');
            ?>
        </label>
    </div>

    <h4><?php echo t('Page Aliases') ?></h4>
    <?php 
    $filters = ['displayAliases' => t('Display page aliases.')];
    foreach ($filters as $handle => $description) {
        ?>
        <div class="checkbox">
            <label class="checkbox span3">
                <input type="checkbox" id="filter_<?php echo $handle; ?>" style="margin-left:-20px;"
                       name="<?php echo $handle; ?>"
                       value="1" <?php if ($controller->$handle == 1) { ?> checked <?php } ?> />
                <?php 
                echo $description;
                ?>
            </label>
        </div>
    <?php 
    }
    ?>
