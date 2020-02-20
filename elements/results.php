<?php  defined('C5_EXECUTE') or die("Access Denied.");

if ($controller->getShowResults()) : ?>
    <div class="sbs_plp_pageResults">
        <?php if (count($controller->pages)) :
            $th = Loader::helper('text');
            $nh = Loader::helper('navigation');
            foreach ($controller->pages as $page):
                // Prepare data for each page being listed...
                $title = $th->entities($page->getCollectionName());
                $url = $nh->getLinkToCollection($page);
                $target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
                $target = empty($target) ? '_self' : $target;
                $description = $page->getCollectionDescription();
                $description = $controller->truncateSummaries ? $th->shorten($description, $controller->truncateChars) : $description;
                $description = $th->entities($description);

                /* The HTML from here through "endforeach" is repeated for every item in the list... */ ?>
                <h3 class="ccm-page-list-title <?php echo PageListPlus::getAdditionalClasses($page);?>">
                    <a href="<?php echo $url ?>" target="<?php echo $target ?>"><?php echo $title ?></a>
                </h3>
                <div class="ccm-page-list-description">
                    <?php echo $description ?>
                </div>

            <?php endforeach; ?>
        <?php  else : ?>
            <p class="sbs_plp_noResults"><?php echo isset($controller->__GET['query']) ? $controller->noResultsText : $controller->noResultsTextOnSearchLoad; ?></p>
        <?php endif; ?>
    </div>
<?php 
endif;
		