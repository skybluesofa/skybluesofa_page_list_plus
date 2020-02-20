<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<select name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][filterSelection]"
        class="pageAttributeInitialSelector form-control"
        data-additional-values=""
        data-default-value="querystring_all not_querystring_all">
    <option
        value="true" <?php if (!$filterSelection || $filterSelection == 'true') print 'selected'; ?>><?php echo t('is true'); ?></option>
    <option
        value="false" <?php if ($filterSelection == 'false') print 'selected'; ?>><?php echo t('is false'); ?></option>
    <option
        value="matches_all" <?php if ($filterSelection == 'matches_all') print 'selected'; ?>><?php echo t('matches current page'); ?></option>
    <option
        value="not_matches_all" <?php if ($filterSelection == 'not_matches_all') print 'selected'; ?>><?php echo t('does not match current page'); ?></option>
    <?php if (!$disallowSearch) { ?>
        <option
            value="querystring_all" <?php if ($filterSelection == 'querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('matches search value'); ?></option>
        <option
            value="not_querystring_all" <?php if ($filterSelection == 'not_querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('does not match search value'); ?></option>
    <?php } ?>
</select>
<div class="pageAttributeDefaultValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, [$values[0], 'querystring_any', 'querystring_all', 'not_querystring_all', 'less_than_querystring', 'less_than_or_equal_to_querystring', 'more_than_querystring', 'more_than_or_equal_to_querystring']) ? "" : "display:none;"); ?>">
    <select name="searchDefaults[<?php echo $pageAttributeKeyID; ?>]" class="form-control">
        <option value="1" <?php echo $values[2] != 0 ? 'selected' : ''; ?>><?php echo t('Yes (1)'); ?></option>
        <option value="0" <?php echo $values[2] == 0 ? 'selected' : ''; ?>><?php echo t('No (0)'); ?></option>
    </select>
</div>