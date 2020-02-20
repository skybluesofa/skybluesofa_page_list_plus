<?php  defined('C5_EXECUTE') or die("Access Denied.");

if ($controller->getShowSearchForm() && $controller->showSearchBox) { ?>
    <div id="sbs_plp_searchBox_<?php echo $controller->getIdentifier(); ?>">
        <input name="query" type="text" value="<?php echo htmlentities($controller->query, ENT_COMPAT, APP_CHARSET); ?>"
               class="sbs_plp_query">
    </div>
<?php 
}