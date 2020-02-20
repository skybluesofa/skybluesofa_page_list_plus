<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<select name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][filterSelection]"
        class="pageAttributeInitialSelector form-control"
        data-additional-values="equals not_equals"
        data-default-value="querystring_all not_querystring_all">
    <option
        value="not_empty" <?php if (!$filterSelection || $filterSelection == 'not_empty') print 'selected'; ?>><?php echo t('has a date'); ?></option>
    <option
        value="is_empty" <?php if ($filterSelection == 'is_empty') print 'selected'; ?>><?php echo t('does not have a date'); ?></option>
    <option value="equals" <?php if ($filterSelection == 'equals') print 'selected'; ?>><?php echo t('is'); ?></option>
    <option
        value="not_equals" <?php if ($filterSelection == 'not_equals') print 'selected'; ?>><?php echo t('is not'); ?></option>
    <option
        value="yesterday" <?php if ($filterSelection == 'yesterday') print 'selected'; ?>><?php echo t('is yesterday'); ?></option>
    <option
        value="today" <?php if ($filterSelection == 'today') print 'selected'; ?>><?php echo t('is today'); ?></option>
    <option
        value="tomorrow" <?php if ($filterSelection == 'tomorrow') print 'selected'; ?>><?php echo t('is tomorrow'); ?></option>
    <?php if ($disallowSearch) { ?>
        <option
            value="querystring_all" <?php if ($filterSelection == 'querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('matches search value'); ?></option>
        <option
            value="not_querystring_all" <?php if ($filterSelection == 'not_querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('does not match search value'); ?></option>
    <?php } ?>
</select>
<div class="pageAttributeAdditionalValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, ['not_empty', 'is_empty', 'equals', 'yesterday', 'today', 'tomorrow', 'matches_all', 'querystring_all', 'not_matches_all', 'not_querystring_all']) ? "display:none;" : ""); ?>">
    <input name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][val1]"
           value="<?php echo $values[0]; ?>"><span class="pageAttributeAdditionalValueSelectionSecondary"
                                                   style="<?php echo(in_array($filterSelection, []) ? "display:none;" : ""); ?>"> <label
            style="float:none;"><?php echo t('and'); ?></label> <input
            name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][val2]"
            value="<?php echo $values[1]; ?>"></span>
</div>
<div class="pageAttributeDefaultValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, [$values[0], 'querystring_all', 'not_querystring_all']) ? "" : "display:none;"); ?>">
    <input name="searchDefaults[<?php echo $pageAttributeKeyID; ?>]" value="<?php echo $values[2]; ?>"
           placeholder="<?php echo t('Default Search Value'); ?>" class="form-control">
</div>