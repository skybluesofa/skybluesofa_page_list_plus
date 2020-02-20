<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<select name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][filterSelection]"
        class="pageAttributeInitialSelector form-control"
        data-additional-values="equals not_equals more_than less_than more_than_or_equal_to less_than_or_equal_to between_inclusive between_exclusive not_between_inclusive not_between_exclusive"
        data-additional-values-secondary="between_inclusive between_exclusive not_between_inclusive not_between_exclusive"
        data-default-value="less_than_querystring more_than_querystring less_than_or_equal_to_querystring more_than_or_equal_to_querystring querystring_all not_querystring_all"
        data-default-value-secondary="between_inclusive between_exclusive not_between_inclusive not_between_exclusive">
    <option
        value="not_empty" <?php if (!$filterSelection || $filterSelection == 'not_empty') print 'selected'; ?>><?php echo t('has a date'); ?></option>
    <option
        value="is_empty" <?php if ($filterSelection == 'is_empty') print 'selected'; ?>><?php echo t('does not have a date'); ?></option>
    <option
        value="equals" <?php if ($filterSelection == 'equals') print 'selected'; ?>><?php echo t('is'); ?></option>
    <option
        value="not_equals" <?php if ($filterSelection == 'not_equals') print 'selected'; ?>><?php echo t('is not'); ?></option>
    <option
        value="yesterday" <?php if ($filterSelection == 'yesterday') print 'selected'; ?>><?php echo t('is yesterday'); ?></option>
    <option
        value="today" <?php if ($filterSelection == 'today') print 'selected'; ?>><?php echo t('is today'); ?></option>
    <option
        value="tomorrow" <?php if ($filterSelection == 'tomorrow') print 'selected'; ?>><?php echo t('is tomorrow'); ?></option>
    <option
        value="last_year" <?php if ($filterSelection == 'last_year') print 'selected'; ?>><?php echo t('is last year'); ?></option>
    <option
        value="this_year" <?php if ($filterSelection == 'this_year') print 'selected'; ?>><?php echo t('is this year'); ?></option>
    <option
        value="next_year" <?php if ($filterSelection == 'next_year') print 'selected'; ?>><?php echo t('is next year'); ?></option>
    <option
        value="last_365" <?php if ($filterSelection == 'last_365') print 'selected'; ?>><?php echo t('is within the last 12 months'); ?></option>
    <option
        value="next_365" <?php if ($filterSelection == 'next_365') print 'selected'; ?>><?php echo t('is within the next 12 months'); ?></option>
    <option
        value="today_and_future" <?php if ($filterSelection == 'today_and_future') print 'selected'; ?>><?php echo t('is today or after'); ?></option>
    <option
        value="not_future" <?php if ($filterSelection == 'not_future') print 'selected'; ?>><?php echo t('is today or before'); ?></option>
    <option
        value="future" <?php if ($filterSelection == 'future') print 'selected'; ?>><?php echo t('is after today'); ?></option>
    <option
        value="past" <?php if ($filterSelection == 'past') print 'selected'; ?>><?php echo t('is before today'); ?></option>
    <option
        value="more_than_or_equal_to" <?php if ($filterSelection == 'more_than_or_equal_to') print 'selected'; ?>><?php echo t('is on or after'); ?></option>
    <option
        value="less_than_or_equal_to" <?php if ($filterSelection == 'less_than_or_equal_to') print 'selected'; ?>><?php echo t('is on or before'); ?></option>
    <option
        value="more_than" <?php if ($filterSelection == 'more_than') print 'selected'; ?>><?php echo t('is after'); ?></option>
    <option
        value="less_than" <?php if ($filterSelection == 'less_than') print 'selected'; ?>><?php echo t('is before'); ?></option>
    <option
        value="more_than_or_equal_to_match" <?php if ($filterSelection == 'more_than_or_equal_to_match') print 'selected'; ?>><?php echo t('is on or after current page'); ?></option>
    <option
        value="less_than_or_equal_to_match" <?php if ($filterSelection == 'less_than_or_equal_to_match') print 'selected'; ?>><?php echo t('is on or before current page'); ?></option>
    <option
        value="more_than_match" <?php if ($filterSelection == 'more_than_match') print 'selected'; ?>><?php echo t('is after current page'); ?></option>
    <option
        value="less_than_match" <?php if ($filterSelection == 'less_than_match') print 'selected'; ?>><?php echo t('is before current page'); ?></option>
    <option
        value="between_inclusive" <?php if ($filterSelection == 'between_inclusive') print 'selected'; ?>><?php echo t('is between (inclusive)'); ?></option>
    <option
        value="between_exclusive" <?php if ($filterSelection == 'between_exclusive') print 'selected'; ?>><?php echo t('is between (exclusive)'); ?></option>
    <option
        value="not_between_inclusive" <?php if ($filterSelection == 'not_between_inclusive') print 'selected'; ?>><?php echo t('is not between (inclusive)'); ?></option>
    <option
        value="not_between_exclusive" <?php if ($filterSelection == 'not_between_exclusive') print 'selected'; ?>><?php echo t('is not between (exclusive)'); ?></option>
    <option
        value="matches_all" <?php if ($filterSelection == 'matches_all') print 'selected'; ?>><?php echo t('matches current page'); ?></option>
    <option
        value="not_matches_all" <?php if ($filterSelection == 'not_matches_all') print 'selected'; ?>><?php echo t('does not match current page'); ?></option>

    <?php if (!$disallowSearch) { ?>
        <option
            value="less_than_querystring" <?php if ($filterSelection == 'less_than_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is before search value'); ?></option>
        <option
            value="more_than_querystring" <?php if ($filterSelection == 'more_than_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is after search value'); ?></option>
        <option
            value="less_than_or_equal_to_querystring" <?php if ($filterSelection == 'less_than_or_equal_to_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is on or before search value'); ?></option>
        <option
            value="more_than_or_equal_to_querystring" <?php if ($filterSelection == 'more_than_or_equal_to_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is on or after search value'); ?></option>
        <option
            value="querystring_all" <?php if ($filterSelection == 'querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is on search value'); ?></option>
        <option
            value="not_querystring_all" <?php if ($filterSelection == 'not_querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is not on match search value'); ?></option>
    <?php } ?>
</select>
<div class="pageAttributeAdditionalValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, ['not_empty', 'is_empty', 'equals', 'yesterday', 'today', 'tomorrow', 'future', 'not_future', 'matches_all', 'querystring_all', 'not_matches_all', 'not_querystring_all', 'less_than_match', 'less_than_querystring', 'less_than_or_equal_to_match', 'less_than_or_equal_to_querystring', 'more_than_match', 'more_than_querystring', 'more_than_or_equal_to_match', 'more_than_or_equal_to_querystring']) ? "display:none;" : ""); ?>">
    <input name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][val1]"
           value="<?php echo $values[0]; ?>"><span class="pageAttributeAdditionalValueSelectionSecondary"
                                                   style="<?php echo(in_array($filterSelection, ['less_than', 'less_than_or_equal_to', 'more_than', 'more_than_or_equal_to']) ? "display:none;" : ""); ?>"> <label
            style="float:none;"><?php echo t('and'); ?></label> <input
            name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][val2]"
            value="<?php echo $values[1]; ?>"></span>
</div>
<div class="pageAttributeDefaultValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, [$values[0], 'querystring_any', 'querystring_all', 'not_querystring_all', 'less_than_querystring', 'less_than_or_equal_to_querystring', 'more_than_querystring', 'more_than_or_equal_to_querystring']) ? "" : "display:none;"); ?>">
    <input name="searchDefaults[<?php echo $pageAttributeKeyID; ?>]" value="<?php echo $values[2]; ?>"
           placeholder="<?php echo t('Default Search Value'); ?>" class="form-control">
</div>	