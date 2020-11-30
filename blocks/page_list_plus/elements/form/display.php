<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<div class="form-group">
    <label class='control-label'><?php echo t('List Title') ?></label>
    <input type="text" name="pageListTitle" value="<?php echo $controller->pageListTitle ?>" class="form-control">
</div>

<div class="form-group">
    <label class='control-label'><?php echo t('Number of Results') ?></label>

    <div class="input-group">
		<span class="input-group-addon">
			<?php echo t('Show'); ?>
		</span>
        <input type="number" name="numberOfResults" min="0"
               value="<?php echo (int)$controller->numberOfResults ? $controller->numberOfResults : '' ?>"
               class="form-control" placeholder="10">
        <span class="input-group-addon"> <?php echo t('items per page') ?></span>
    </div>
    <div class="input-group">
		<span class="input-group-addon">
			<?php echo t('Skip the first'); ?>
		</span>
        <input type="number" name="skipTopNumberOfResults" min="0"
               value="<?php echo (int)$controller->skipTopNumberOfResults ? $controller->skipTopNumberOfResults : '' ?>"
               class="form-control" placeholder="0">
        <span class="input-group-addon"> <?php echo t('items') ?></span>
    </div>
</div>

<div class="form-group">
    <div class="checkbox">
        <label style="text-align:left;">
            <input type="checkbox" name="showSeeAllLink"
                   value="1" <?php if ($controller->showSeeAllLink == 1) { ?> checked <?php } ?> id="showSeeAllLink"/>
            <?php echo t("Display 'See All' link if more items are available than are displayed"); ?>
        </label>
    </div>
    <div class="clearfix sbs_plp_hider"
         style="margin-left:20px;<?php if ($controller->showSeeAllLink != 1) { ?>display:none;<?php } ?>">
        <div class="input-group">
            <input class="form-control" id="seeAllLinkText"
                   type="text" name="seeAllLinkText" value="<?php echo $controller->seeAllLinkText; ?>"/>
			<span class="input-group-addon">
				<?php echo t("'Show All' Text") ?>
			</span>
        </div>
        <div class="input-group">
            <input class="form-control" id="seeAllLinkUrl"
                   type="text" name="seeAllLinkUrl" value="<?php echo $controller->seeAllLinkUrl; ?>"/>
			<span class="input-group-addon">
				<?php echo t("'Show All' URL") ?>
			</span>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="checkbox">
        <label style="text-align:left;">
            <input type="checkbox" name="paginateResults"
                   value="1" <?php if ($controller->paginateResults == 1) { ?> checked <?php } ?> />
            <?php echo t('Display pagination interface if more items are available than are displayed'); ?>
        </label>
    </div>
</div>

<div class="form-group">
    <label class='control-label'><?php echo t("'No Results' text"); ?></label>
    <input type="text" name="noResultsText" id="noResultsText" maxlength="255"
           value="<?php echo $controller->noResultsText ?>" class="form-control">
</div>

<div class="form-group">
    <label class='control-label'><?php echo t("'No Results' text on Search Page Load"); ?></label>
    <input type="text" name="noResultsTextOnSearchLoad" id="noResultsTextOnSearchLoad" maxlength="255"
           value="<?php echo $controller->noResultsTextOnSearchLoad ?>" class="form-control">
</div>

<div class="form-group">
    <div class="checkbox">
        <label style="text-align:left;">
            <input type="checkbox" name="includeThumbnail"
                   value="1" <?php if ($controller->includeThumbnail == 1) { ?> checked <?php } ?>
                   id="includeThumbnail"/>
            <?php echo t("Show thumbnail, if available"); ?>
        </label>
    </div>
    <div class="clearfix sbs_plp_hider"
         style="margin-left:20px;<?php if ($controller->includeThumbnail != 1) { ?>display:none;<?php } ?>">
        <div class="input-group">
            <input class="form-control" id="thumbnailHandles" placeholder="<?php  t('thumbnail'); ?>"
                   type="text" name="thumbnailHandles"
                   value="<?php echo(is_array($controller->thumbnailHandles) ? implode(',', $controller->thumbnailHandles) : $controller->thumbnailHandles); ?>"/>
			<span class="input-group-addon">
				<?php echo t('Comma Separated Image/File Handles') ?>
			</span>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="checkbox">
        <label style="text-align:left;">
            <input type="checkbox" name="includeName"
                   value="1" <?php if ($controller->includeName == 1) { ?> checked <?php } ?> id="includeName"/>
            <?php echo t("Show the page name in results"); ?>
        </label>
    </div>
</div>

<div class="form-group">
    <div class="checkbox">
        <label style="text-align:left;">
            <input type="checkbox" name="includeDescription"
                   value="1" <?php if ($controller->includeDescription == 1) { ?> checked <?php } ?>
                   id="includeDescription"/>
            <?php echo t("Show the page description in results"); ?>
        </label>
    </div>
    <div class="form-group"
         style="margin-left:21px;width:252px;<?php if ($controller->includeDescription != 1) { ?>display:none;<?php } ?>">
        <div class="input-group">
		<span class="input-group-addon">
			<input id="ccm-pagelist-truncateSummariesOn" name="truncateSummaries" type="checkbox"
                   value="1" <?php echo($controller->truncateSummaries ? "checked=\"checked\"" : "") ?> />
		</span>
            <input class="form-control"
                   id="ccm-pagelist-truncateChars" <?php echo($controller->truncateSummaries ? "" : "disabled=\"disabled\"") ?>
                   type="number" min="0" name="truncateLength" size="3" value="<?php echo intval($controller->truncateLength) ?>"/>
		<span class="input-group-addon">
			<?php echo t('truncated characters') ?>
		</span>
        </div>
    </div>
</div>


<div class="form-group">
    <div class="checkbox">
        <label style="text-align:left;">
            <input type="checkbox" name="includeDate"
                   value="1" <?php if ($controller->includeDate == 1) { ?> checked <?php } ?> id="includeDate"/>
            <?php echo t("Show the date the page was made public"); ?>
        </label>
    </div>
</div>

<div class="form-group">
    <div class="checkbox">
        <label style="text-align:left;">
            <input type="checkbox" name="useButtonForLink"
                   value="1" <?php if ($controller->useButtonForLink == 1) { ?> checked <?php } ?>
                   id="useButtonForLink"/>
            <?php echo t("Instead of linking the page name, use a button for the link"); ?>
        </label>
    </div>
    <div class="clearfix sbs_plp_hider"
         style="margin-left:20px;<?php if ($controller->useButtonForLink != 1) { ?>display:none;<?php } ?>">
        <div class="input-group">
            <input class="form-control" id="buttonLinkText" placeholder="<?php  t('View'); ?>"
                   type="text" name="buttonLinkText" size="3" value="<?php echo $controller->buttonLinkText; ?>"/>
			<span class="input-group-addon">
				<?php echo t('Text on Button') ?>
			</span>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="checkbox">
        <label style="text-align:left;">
            <input type="checkbox" name="provideRssFeed"
                   value="1" <?php if ($controller->provideRssFeed == 1) { ?> checked <?php } ?> id="provideRssFeed"/>
            <?php echo t("Provide RSS Feed"); ?>
        </label>
    </div>
    <div class="clearfix sbs_plp_hider"
         style="margin-left:20px;<?php if ($controller->provideRssFeed != 1) { ?>display:none;<?php } ?>">
        <?php if (is_object($rssFeed)) { ?>
            <input type="hidden" name="pfID"
                   value="<?php echo $controller->pfID; ?>"/>
            <?php echo t('RSS Feed can be found here: <a href="%s" target="_blank">%s</a>', $rssFeed->getFeedURL(), $rssFeed->getFeedURL()) ?>
        <?php } else { ?>
            <div class="form-group">
                <label class="control-label"><?php echo t('RSS Feed Title') ?></label>
                <input class="form-control" id="rssFeedTitle" type="text" name="rssFeedTitle"
                       value="<?php echo $controller->rssFeedTitle; ?>"/>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo t('RSS Feed Description') ?></label>
                <textarea name="rssFeedDescription" class="form-control" placeholder="<?php echo t('The description of the feed.');?>"><?php echo $controller->rssFeedDescription; ?></textarea>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo t('RSS Feed Location') ?></label>

                <div class="input-group">
                    <span class="input-group-addon"><?php echo URL::to('/rss') ?>/</span>
                    <input type="text" name="rssHandle" value="<?php echo $controller->rssHandle; ?>" id="rssHandle"/>
                </div>
            </div>
        <?php } ?>

    </div>
</div>

<div class="form-group">
    <div class="checkbox">
        <label style="text-align:left;">
            <input type="checkbox" name="showDebugInformation"
                   value="1" <?php if ($controller->showDebugInformation == 1) { ?> checked <?php } ?>
                   id="showDebugInformation"/>
            <?php echo t('Show Debugging information for this block'); ?>
        </label>
    </div>
    <div class="sbs_plp_hider" id=""
         style="padding-left:20px;<?php if ($controller->showDebugInformation != 1) { ?>display:none;<?php } ?>">
        <select name="showDebugInformationLocation" class="form-control">
            <option
                value="console" <?php if ($controller->showDebugInformationLocation == 'console') print 'selected'; ?>><?php echo t('In the Console'); ?></option>
            <option
                value="onscreen" <?php if ($controller->showDebugInformationLocation == 'onscreen') print 'selected'; ?>><?php echo t('On-screen'); ?></option>
            <option
                value="onscreen-console" <?php if ($controller->showDebugInformationLocation == 'onscreen-console') print 'selected'; ?>><?php echo t('Both On-screen and in the Console'); ?></option>
        </select>
    </div>

</div>
