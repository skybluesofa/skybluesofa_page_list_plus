<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$c = Page::getCurrentPage();
$dateHelper = Core::make('helper/date');
/* @var $dateHelper \Concrete\Core\Localization\Service\Date */
$navigationHelper = Core::make('helper/navigation');
$textHelper = Core::make('helper/text');

$wrapperClasses = ['ccm-block-page-list-wrapper', 'sbs_plp_container'];
if ($controller->allowPagination) $wrapperClasses[] = 'sbs_plp_hasPagination';
if (($controller->showSearchFilters || $controller->showSearchBox) && $controller->submitViaAjax) $wrapperClasses[] = 'sbs_plp_submitViaAjax';
if ($controller->showSearchResults && $controller->receiveViaAjax) $wrapperClasses[] = 'sbs_plp_receiveViaAjax';
$wrapperClasses = implode(' ', $wrapperClasses);
?>

<div class="<?php echo $wrapperClasses; ?>" id="sbs_plp_container-<?php echo $controller->getIdentifier()?>"
     data-bID="<?php echo $controller->getIdentifier(); ?>" data-cID="<?php echo $c->cID; ?>" style="position:relative;">
    <input type="hidden" class="reloadURL"
           value="<?php echo URL::route(array('/reload', 'skybluesofa_page_list_plus')); ?>">
    <?php 
    echo Loader::element('title', ['controller' => $controller], 'skybluesofa_page_list_plus');
    echo Loader::element('form', ['controller' => $controller], 'skybluesofa_page_list_plus');
    echo Loader::element('rss', ['controller' => $controller], 'skybluesofa_page_list_plus');
    ?>

    <?php if ($c->isEditMode() && $controller->isBlockEmpty()) { ?>
        <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Page List+ Block.') ?></div>
    <?php } else { ?>
        <div class="ccm-block-page-list-pages">
                <?php  foreach ($controller->pages as $page):

                // Prepare data for each page being listed...
                $buttonClasses = 'ccm-block-page-list-read-more';
                $entryClasses = 'ccm-block-page-list-page-entry';
                $title = $textHelper->entities($page->getCollectionName());
                $url = $navigationHelper->getLinkToCollection($page);
                $target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
                $target = empty($target) ? '_self' : $target;
                $description = $page->getCollectionDescription();
                $description = $controller->truncateSummaries ? $textHelper->wordSafeShortText($description, $controller->truncateChars) : $description;
                $description = $textHelper->entities($description);
                $thumbnail = false;
                if ($controller->includeThumbnail) {
                    $thumbnail = $controller->getThumbnailAttribute($page);
                }
                $includeEntryText = false;
                if ($controller->includeName || $controller->includeDescription || $controller->useButtonForLink) {
                    $includeEntryText = true;
                }
                if (is_object($thumbnail) && $includeEntryText) {
                    $entryClasses = 'ccm-block-page-list-page-entry-horizontal';
                }

                $date = $dateHelper->formatDateTime($page->getCollectionDatePublic(), true);


                //Other useful page data...


                //$last_edited_by = $page->getVersionObject()->getVersionAuthorUserName();

                //$original_author = Page::getByID($page->getCollectionID(), 1)->getVersionObject()->getVersionAuthorUserName();

                /* CUSTOM ATTRIBUTE EXAMPLES:
                 * $example_value = $page->getAttribute('example_attribute_handle');
                 *
                 * HOW TO USE IMAGE ATTRIBUTES:
                 * 1) Uncomment the "$ih = Loader::helper('image');" line up top.
                 * 2) Put in some code here like the following 2 lines:
                 *      $img = $page->getAttribute('example_image_attribute_handle');
                 *      $textHelperumb = $ih->getThumbnail($img, 64, 9999, false);
                 *    (Replace "64" with max width, "9999" with max height. The "9999" effectively means "no maximum size" for that particular dimension.)
                 *    (Change the last argument from false to true if you want thumbnails cropped.)
                 * 3) Output the image tag below like this:
                 *		<img src="<?php echo $textHelperumb->src ?>" width="<?php echo $textHelperumb->width ?>" height="<?php echo $textHelperumb->height ?>" alt="" />
                 *
                 * ~OR~ IF YOU DO NOT WANT IMAGES TO BE RESIZED:
                 * 1) Put in some code here like the following 2 lines:
                 * 	    $img_src = $img->getRelativePath();
                 *      $img_width = $img->getAttribute('width');
                 *      $img_height = $img->getAttribute('height');
                 * 2) Output the image tag below like this:
                 * 	    <img src="<?php echo $img_src ?>" width="<?php echo $img_width ?>" height="<?php echo $img_height ?>" alt="" />
                 */

                /* End data preparation. */

                /* The HTML from here through "endforeach" is repeated for every item in the list... */ ?>

                <div class="<?php echo $entryClasses?>">
                    <?php if (is_object($thumbnail)): ?>
                        <div class="ccm-block-page-list-page-entry-thumbnail">
                            <?php 
                            $img = Core::make('html/image', [$thumbnail]);
                            $tag = $img->getTag();
                            $tag->addClass('img-responsive');
                            print $tag;
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($includeEntryText): ?>
                        <div class="ccm-block-page-list-page-entry-text">

                            <?php if ($controller->includeName): ?>
                                <div class="ccm-block-page-list-title">
                                    <?php if ($controller->useButtonForLink) { ?>
                                        <?php echo $title; ?>
                                    <?php } else { ?>
                                        <a href="<?php echo $url ?>"
                                           target="<?php echo $target ?>"><?php echo $title ?></a>
                                    <?php } ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($controller->includeDate): ?>
                                <div class="ccm-block-page-list-date"><?php echo $date ?></div>
                            <?php endif; ?>

                            <?php if ($controller->includeDescription): ?>
                                <div class="ccm-block-page-list-description">
                                    <?php echo $description ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($controller->useButtonForLink): ?>
                                <div class="ccm-block-page-list-page-entry-read-more">
                                    <a href="<?php echo $url ?>"
                                       class="<?php echo $buttonClasses ?>"><?php echo $buttonLinkText ? $buttonLinkText : t('View'); ?></a>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>
        </div>

        <?php if (count($pages) == 0): ?>
            <div class="ccm-block-page-list-no-pages"><?php echo $noResultsText ?></div>
        <?php endif; ?>

        <?php echo Loader::element('pagination', ['controller' => $controller], 'skybluesofa_page_list_plus'); ?>
    <?php 
    }
    echo Loader::element('debug', ['controller' => $controller], 'skybluesofa_page_list_plus');
    ?>
</div>