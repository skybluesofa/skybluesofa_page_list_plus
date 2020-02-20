<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$th = Loader::helper('text');
$nh = Core::make('helper/navigation');

$c = Page::getCurrentPage();
?>

<div class="ccm-block-page-list-thumbnail-grid-wrapper">

    <?php echo Loader::element('title', ['controller' => $controller], 'skybluesofa_page_list_plus'); ?>

    <?php foreach ($controller->pages as $page):

        $title = $th->entities($page->getCollectionName());
        $url = $nh->getLinkToCollection($page);
        $target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
        $target = empty($target) ? '_self' : $target;
        $thumbnail = false;
        if ($controller->includeThumbnail) {
            $thumbnail = $controller->getThumbnailAttribute($page);
        }
        $hoverLinkText = $title;
        $description = $page->getCollectionDescription();
        $description = $controller->truncateSummaries ? $th->wordSafeShortText($description, $controller->truncateChars) : $description;
        $description = $th->entities($description);
        if ($useButtonForLink) {
            $hoverLinkText = $buttonLinkText;
        }

        ?>

        <div class="ccm-block-page-list-page-entry-grid-item">

        <?php if (is_object($thumbnail)): ?>
            <div class="ccm-block-page-list-page-entry-grid-thumbnail">
                <a href="<?php echo $url ?>" target="<?php echo $target ?>"><?php 
                $img = Core::make('html/image', [$thumbnail]);
                $tag = $img->getTag();
                $tag->addClass('img-responsive');
                print $tag;
                ?>
                    <div class="ccm-block-page-list-page-entry-grid-thumbnail-hover">
                        <div class="ccm-block-page-list-page-entry-grid-thumbnail-title-wrapper">
                        <div class="ccm-block-page-list-page-entry-grid-thumbnail-title">
                            <i class="ccm-block-page-list-page-entry-grid-thumbnail-icon"></i>
                            <?php echo $hoverLinkText?>
                        </div>
                        </div>
                    </div>
                </a>

                <?php if ($controller->useButtonForLink) { ?>
                <div class="ccm-block-page-list-title">
                    <?php echo $title; ?>
                </div>
                <?php } ?>

                <?php if ($controller->includeDate): ?>
                    <div class="ccm-block-page-list-date"><?php echo $date?></div>
                <?php endif; ?>

                <?php if ($controller->includeDescription): ?>
                    <div class="ccm-block-page-list-description">
                        <?php echo $description ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        </div>

	<?php endforeach; ?>

    <?php if (count($pages) == 0): ?>
        <div class="ccm-block-page-list-no-pages"><?php echo h($noResultsText)?></div>
    <?php endif;?>

</div>

<?php if ($controller->paginateResults): ?>
    <?php echo $controller->pagination; ?>
<?php endif; ?>

<?php if ( $c->isEditMode() && $controller->isBlockEmpty()): ?>
    <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Page List Block.')?></div>
<?php endif; ?>
<?php echo Loader::element('debug', ['controller' => $controller], 'skybluesofa_page_list_plus'); ?>
