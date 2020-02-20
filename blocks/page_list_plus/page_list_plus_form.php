<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$currentPage = Page::getCurrentPage();
?>
<div class="skybluesofa_page_list_plus_edit_column skybluesofa_page_list_plus_form_column">
    <?php echo Loader::helper('concrete/ui')->tabs($controller->getFormTabs()); ?>
    <input type="hidden" name="previewUrl"
           value="<?php echo URL::route(['/preview', 'skybluesofa_page_list_plus']); ?>"/>
    <input type="hidden" name="cID" value="<?php echo $currentPage->cID; ?>">
    <input type="hidden" name="existingFeedHandles"
           value=",<?php echo strtolower(implode(",",$feedHandles))?>,"/>
    <?php  foreach ($controller->getFormCards() as $card) { ?>
        <div class="ccm-tab-content" id="ccm-tab-content-<?php echo $card['id']; ?>">
            <?php  $this->inc('elements/form/' . $card['element'] . '.php', ['controller' => $controller]); ?>
        </div>
    <?php } ?>
    <div class="loader" style="line-height:34px;position:absolute;">
        <i class="fa fa-cog fa-spin"></i>
    </div>
</div>
<div class="skybluesofa_page_list_plus_edit_column skybluesofa_page_list_plus_preview_column">
    <?php  $this->inc('elements/form/preview.php', ['controller' => $controller]); ?>
</div>
<script>Concrete.event.publish('skybluesofa_page_list_plus.edit.open');</script>
<style>
    .skybluesofa_page_list_plus_form_column LEGEND {
        margin-bottom: 0;
        padding-top: 7px;
        border: 0;
    }

    .skybluesofa_page_list_plus_edit_column {
        position: absolute;
        overflow: scroll;
    }

    .skybluesofa_page_list_plus_form_column {
        top: 0;
        right: 330px;
        bottom: 0;
        left: 0;
        padding-top: 20px;
        padding-left: 20px;
    }

    .skybluesofa_page_list_plus_form_column .ccm-tab-content {
        padding-right: 25px;
    }

    .skybluesofa_page_list_plus_preview_column {
        width: 330px;
        top: 0;
        right: 0;
        bottom: 0;
        padding-right: 20px;
    }

    #sbs_plp_innerScroll FIELDSET {
        margin-bottom: 20px;
    }

    #sbs_plp_innerScroll LEGEND {
        margin-bottom: 0;
    }

    .ui-dialog .ui-dialog-content {
        overflow: hidden;
    }
</style>
