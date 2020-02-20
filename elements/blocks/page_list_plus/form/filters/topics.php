<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<select name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][filterSelection]"
        class="pageAttributeInitialSelector form-control"
        data-additional-values="contains not_contains is_exactly is_not_exactly starts_with not_starts_with node_starts_with node_not_starts_with"
        data-default-value="querystring_all not_querystring_all querystring_contains not_querystring_contains">
    <option
        value="not_empty" <?php if (!$filterSelection || $filterSelection == 'not_empty') print 'selected'; ?>><?php echo t('is not empty'); ?></option>
    <option
        value="is_empty" <?php if ($filterSelection == 'is_empty') print 'selected'; ?>><?php echo t('is empty'); ?></option>
    <option
        value="contains" <?php if ($filterSelection == 'contains') print 'selected'; ?>><?php echo t('contains'); ?></option>
    <option
        value="not_contains" <?php if ($filterSelection == 'not_contains') print 'selected'; ?>><?php echo t('does not contain'); ?></option>
    <option
        value="is_exactly" <?php if ($filterSelection == 'is_exactly') print 'selected'; ?>><?php echo t('is'); ?></option>
    <option
        value="is_not_exactly" <?php if ($filterSelection == 'is_not_exactly') print 'selected'; ?>><?php echo t('is not'); ?></option>
    <option
        value="starts_with" <?php if ($filterSelection == 'starts_with') print 'selected'; ?>><?php echo t('full topic starts with'); ?></option>
    <option
        value="not_starts_with" <?php if ($filterSelection == 'not_starts_with') print 'selected'; ?>><?php echo t('full topic does not start with'); ?></option>
    <option
        value="node_starts_with" <?php if ($filterSelection == 'node_starts_with') print 'selected'; ?>><?php echo t('branch topic starts with'); ?></option>
    <option
        value="node_not_starts_with" <?php if ($filterSelection == 'node_not_starts_with') print 'selected'; ?>><?php echo t('branch topic does not start with'); ?></option>
    <option
        value="matches_all" <?php if ($filterSelection == 'matches_all') print 'selected'; ?>><?php echo t('matches all from the current page'); ?></option>
    <option
        value="matches_any" <?php if ($filterSelection == 'matches_any') print 'selected'; ?>><?php echo t('matches any from the current page'); ?></option>
    <option
        value="not_matches_any" <?php if ($filterSelection == 'not_matches_any') print 'selected'; ?>><?php echo t('does not match any from the current page'); ?></option>
    <?php if (!$disallowSearch) { ?>
        <option
            value="querystring_contains" <?php if ($filterSelection == 'querystring_contains') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('contains search value'); ?></option>
        <option
            value="not_querystring_contains" <?php if ($filterSelection == 'not_querystring_contains') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('does not contain search value'); ?></option>
        <option
            value="querystring_all" <?php if ($filterSelection == 'querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('matches search value'); ?></option>
        <option
            value="not_querystring_all" <?php if ($filterSelection == 'not_querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('does not match search value'); ?></option>
    <?php } ?>
</select>
<div class="pageAttributeAdditionalValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, [$values[0], 'is_empty', 'not_empty', 'matches_all', 'not_matches_all', 'querystring_all', 'not_querystring_all','querystring_contains','not_querystring_contains']) ? "display:none;" : ""); ?>">
    <input name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][val1]"
           value="<?php echo $values[0]; ?>" placeholder="<?php echo t('Match Value'); ?>" class="form-control">
</div>
<div class="pageAttributeDefaultValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, [$values[0], 'querystring_any', 'querystring_all', 'not_querystring_all', 'querystring_contains', 'not_querystring_contains', 'less_than_querystring', 'less_than_or_equal_to_querystring', 'more_than_querystring', 'more_than_or_equal_to_querystring']) ? "" : "display:none;"); ?>">
    <input name="searchDefaults[<?php echo $pageAttributeKeyID; ?>]" value="<?php echo $values[2]; ?>"
           placeholder="<?php echo t('Default Search Value'); ?>" class="form-control">
</div>