<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php if (!$controller->useForSearch || ($controller->useForSearch && $controller->showSearchResults)) : ?>
    <?php if ($controller->showSeeAllLink) : ?>
        <div class="seeAll">
            <div class="ccm-spacer"></div>
            <p><a href="<?php echo $controller->seeAllLinkUrl; ?>"><?php echo $controller->seeAllLinkText; ?></a></p>
        </div>
    <?php endif; ?>
    <?php if ($controller->paginateResults): ?>
        <div class="ccm-pagination-wrapper">
            <?php echo $controller->pagination; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
