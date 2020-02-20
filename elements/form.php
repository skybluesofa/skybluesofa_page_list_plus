<?php  defined('C5_EXECUTE') or die("Access Denied.");

$nh = Loader::helper('navigation');

if ($controller->getShowSearchForm() || $controller->getShowSorting()) { ?>
    <form
        action="<?php echo $controller->searchBoxTargetURL ? $controller->searchBoxTargetURL : $nh->getLinkToCollection(Page::getCurrentPage()); ?>"
        method="get" data-serialized="<?php echo $_SERVER['query_string']; ?>"
        id="sbs_plp_form_<?php echo $controller->getIdentifier(); ?>"
        class="ccm-search-block-form" style="margin-bottom:0;position:relative;">
        <?php 
        echo Loader::element('form/searchbox', ['controller' => $controller], 'skybluesofa_page_list_plus');
        echo Loader::element('form/filters', ['controller' => $controller], 'skybluesofa_page_list_plus');
        echo Loader::element('form/js', ['controller' => $controller], 'skybluesofa_page_list_plus');
        echo Loader::element('form/sorting', ['controller' => $controller], 'skybluesofa_page_list_plus');
        echo Loader::element('form/submit', ['controller' => $controller], 'skybluesofa_page_list_plus');
        echo Loader::element('form/loading', ['controller' => $controller], 'skybluesofa_page_list_plus');
        ?>
    </form>
    <div class="sbs_plp_searchFilterClear"></div>
<?php 
}