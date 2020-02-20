<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<select name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][filterSelection]"
        class="pageAttributeInitialSelector form-control"
        data-additional-values="id_is_exactly id_is_not_exactly contains not_contains is_exactly starts_with ends_with"
        data-additional-values-secondary=""
        data-default-value="querystring_all not_querystring_all"
        data-default-value-secondary="">
    <option
        value="not_empty" <?php if (!$filterSelection || $filterSelection == 'not_empty') print 'selected'; ?>><?php echo t('is not empty'); ?></option>
    <option
        value="is_empty" <?php if ($filterSelection == 'is_empty') print 'selected'; ?>><?php echo t('is empty'); ?></option>
    <option
        value="id_is_exactly" <?php if ($filterSelection == 'id_is_exactly') print 'selected'; ?>><?php echo t('selected page ID is'); ?></option>
    <option
        value="id_is_not_exactly" <?php if ($filterSelection == 'id_is_not_exactly') print 'selected'; ?>><?php echo t('selected page ID is not'); ?></option>
    <option
        value="is_exactly" <?php if ($filterSelection == 'is_exactly') print 'selected'; ?>><?php echo t('selected page name is'); ?></option>
    <option
        value="contains" <?php if ($filterSelection == 'contains') print 'selected'; ?>><?php echo t('selected page name contains'); ?></option>
    <option
        value="not_contains" <?php if ($filterSelection == 'not_contains') print 'selected'; ?>><?php echo t('selected page name does not contain'); ?></option>
    <option
        value="starts_with" <?php if ($filterSelection == 'starts_with') print 'selected'; ?>><?php echo t('selected page name starts with'); ?></option>
    <option
        value="ends_with" <?php if ($filterSelection == 'ends_with') print 'selected'; ?>><?php echo t('selected page name ends with'); ?></option>
    <option
        value="id_matches" <?php if ($filterSelection == 'id_matches') print 'selected'; ?>><?php echo t('matches current page ID'); ?></option>
    <option
        value="id_not_matches" <?php if ($filterSelection == 'id_not_matches') print 'selected'; ?>><?php echo t('does not match current page ID'); ?></option>
    <option
        value="matches_all" <?php if ($filterSelection == 'matches_all') print 'selected'; ?>><?php echo t("matches '%s' attribute for the current page", $pageAttribute->getAttributeKeyName()); ?></option>
    <option
        value="not_matches_all" <?php if ($filterSelection == 'not_matches_all') print 'selected'; ?>><?php echo t("does not match '%s' attribute for the current page", $pageAttribute->getAttributeKeyName()); ?></option>
    <?php if (!$disallowSearch) { ?>
        <option
            value="not_querystring_all" <?php if ($filterSelection == 'not_querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('does not match search value'); ?></option>
        <option
            value="querystring_all" <?php if ($filterSelection == 'querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('matches search value'); ?></option>
    <?php } ?>
</select>
<div class="pageAttributeAdditionalValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, [$values[1], 'matches_all', 'querystring_all', 'not_matches_all', 'not_querystring_all']) ? "display:none;" : ""); ?>">
    <input name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][val1]"
           value="<?php echo $values[1]; ?>" placeholder="<?php echo t('Match Value'); ?>" class="form-control">
</div>
<div class="pageAttributeDefaultValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, [$values[1], 'querystring_any', 'querystring_all', 'not_querystring_all', 'less_than_querystring', 'less_than_or_equal_to_querystring', 'more_than_querystring', 'more_than_or_equal_to_querystring']) ? "" : "display:none;"); ?>">
    <input name="searchDefaults[<?php echo $pageAttributeKeyID; ?>]" value="<?php echo $values[2]; ?>"
           placeholder="<?php echo t('Default Search Value'); ?>" class="form-control">
</div>