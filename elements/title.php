<?php  defined('C5_EXECUTE') or die("Access Denied.");

if ($controller->getShowTitle()) : ?>
    <div class="ccm-block-page-list-header">
        <h5><?php echo $controller->pageListTitle ?></h5>
    </div>
<?php endif; ?>