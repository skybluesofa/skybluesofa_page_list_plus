<?php  defined('C5_EXECUTE') or die("Access Denied.");

if ($controller->getShowRss()) :
    ?>
    <a href="<?php echo $controller->rssUrl ?>" target="_blank" class="ccm-block-page-list-rss-feed"><i
            class="fa fa-rss"></i></a>
    <link href="<?php echo BASE_URL . $controller->rssUrl ?>" rel="alternate" type="application/rss+xml"
          title="<?php echo $controller->rssTitle; ?>"/>
<?php 
endif;
