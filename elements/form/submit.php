<?php  defined('C5_EXECUTE') or die("Access Denied.");

if ($controller->getShowSearchForm() || $controller->getShowSorting()) { ?>
    <?php if (!empty($controller->searchBoxButtonText)) { ?>
        <input name="submitform" type="submit" value="<?php echo $controller->searchBoxButtonText; ?>"
               class="sbs_plp_submit">
    <?php } ?>
<?php 
}