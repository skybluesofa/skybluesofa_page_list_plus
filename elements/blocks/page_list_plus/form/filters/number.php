<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<select name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][filterSelection]"
        class="pageAttributeInitialSelector form-control"
        data-additional-values="less_than less_than_allow_negatives more_than less_than_or_equal_to less_than_or_equal_to_allow_negatives more_than_or_equal_to between_inclusive between_exclusive not_between_inclusive not_between_exclusive"
        data-additional-values-secondary="between_inclusive between_exclusive not_between_inclusive not_between_exclusive between_inclusive_querystring between_exclusive_querystring not_between_inclusive_querystring not_between_exclusive_querystring"
        data-default-value="less_than_querystring less_than_querystring_allow_negatives less_than_or_equal_to_querystring less_than_or_equal_to_querystring_allow_negatives more_than_querystring more_than_or_equal_to_querystring between_inclusive_querystring between_exclusive_querystring not_between_inclusive_querystring not_between_exclusive_querystring querystring_all not_querystring_all"
        data-default-value-secondary="between_inclusive between_exclusive not_between_inclusive not_between_exclusive between_inclusive_querystring between_exclusive_querystring not_between_inclusive_querystring not_between_exclusive_querystring">
    <option
        value="not_empty" <?php if ($filterSelection == 'not_empty') print 'selected'; ?>><?php echo t('is not empty'); ?></option>
    <option
        value="is_empty" <?php if ($filterSelection == 'is_empty') print 'selected'; ?>><?php echo t('is empty'); ?></option>
    <option
        value="less_than" <?php if ($filterSelection == 'less_than') print 'selected'; ?>><?php echo t('is less than'); ?></option>
    <option
        value="less_than_allow_negatives" <?php if ($filterSelection == 'less_than_allow_negatives') print 'selected'; ?>><?php echo t('is less than (allow negative numbers)'); ?></option>
    <option
        value="more_than" <?php if ($filterSelection == 'more_than') print 'selected'; ?>><?php echo t('is more than'); ?></option>
    <option
        value="less_than_or_equal_to" <?php if ($filterSelection == 'less_than_or_equal_to') print 'selected'; ?>><?php echo t('is less than or equal to'); ?></option>
    <option
        value="less_than_or_equal_to_allow_negatives" <?php if ($filterSelection == 'less_than_or_equal_to_allow_negatives') print 'selected'; ?>><?php echo t('is less than or equal to (allow negative numbers)'); ?></option>
    <option
        value="more_than_or_equal_to" <?php if ($filterSelection == 'more_than_or_equal_to') print 'selected'; ?>><?php echo t('is more than or equal to'); ?></option>
    <option
        value="between_inclusive" <?php if ($filterSelection == 'between_inclusive') print 'selected'; ?>><?php echo t('is between (inclusive)'); ?></option>
    <option
        value="not_between_inclusive" <?php if ($filterSelection == 'not_between_inclusive') print 'selected'; ?>><?php echo t('is not between (inclusive)'); ?></option>
    <option
        value="between_exclusive" <?php if ($filterSelection == 'between_exclusive') print 'selected'; ?>><?php echo t('is between (exclusive)'); ?></option>
    <option
        value="not_between_exclusive" <?php if ($filterSelection == 'not_between_exclusive') print 'selected'; ?>><?php echo t('is not between (exclusive)'); ?></option>
    <option
        value="less_than_match" <?php if ($filterSelection == 'less_than_match') print 'selected'; ?>><?php echo t('is less than current page'); ?></option>
    <option
        value="less_than_match_allow_negatives" <?php if ($filterSelection == 'less_than_match_allow_negatives') print 'selected'; ?>><?php echo t('is less than current page (allow negative numbers)'); ?></option>
    <option
        value="more_than_match" <?php if ($filterSelection == 'more_than_match') print 'selected'; ?>><?php echo t('is more than current page'); ?></option>
    <option
        value="less_than_or_equal_to_match" <?php if ($filterSelection == 'less_than_or_equal_to_match') print 'selected'; ?>><?php echo t('is less than or equal to current page'); ?></option>
    <option
        value="less_than_or_equal_to_match_allow_negatives" <?php if ($filterSelection == 'less_than_or_equal_to_match_allow_negatives') print 'selected'; ?>><?php echo t('is less than or equal to current page (allow negative numbers)'); ?></option>
    <option
        value="more_than_or_equal_to_match" <?php if ($filterSelection == 'more_than_or_equal_to_match') print 'selected'; ?>><?php echo t('is more than or equal to current page'); ?></option>
    <option
        value="matches_all" <?php if ($filterSelection == 'matches_all') print 'selected'; ?>><?php echo t('matches current page'); ?></option>
    <option
        value="not_matches_all" <?php if ($filterSelection == 'not_matches_all') print 'selected'; ?>><?php echo t('does not match current page'); ?></option>

    <?php if (!$disallowSearch) { ?>
        <option
            value="less_than_querystring" <?php if ($filterSelection == 'less_than_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is less than search value'); ?></option>
        <option
            value="less_than_querystring_allow_negatives" <?php if ($filterSelection == 'less_than_querystring_allow_negatives') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is less than search value (allow negative numbers)'); ?></option>
        <option
            value="less_than_or_equal_to_querystring" <?php if ($filterSelection == 'less_than_or_equal_to_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is less than or equal to search value'); ?></option>
        <option
            value="less_than_or_equal_to_querystring_allow_negatives" <?php if ($filterSelection == 'less_than_or_equal_to_querystring_allow_negatives') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is less than or equal to search value (allow negative_numbers)'); ?></option>
        <option
            value="more_than_querystring" <?php if ($filterSelection == 'more_than_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is more than search value'); ?></option>
        <option
            value="more_than_or_equal_to_querystring" <?php if ($filterSelection == 'more_than_or_equal_to_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is more than or equal to search value'); ?></option>
        <option
            value="between_inclusive_querystring" <?php if ($filterSelection == 'between_inclusive_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?> class="sbs_plp_searchValue"><?php echo t('is between (inclusive) the search values'); ?></option>
        <option
            value="between_exclusive_querystring" <?php if ($filterSelection == 'between_exclusive_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?> class="sbs_plp_searchValue"><?php echo t('is between (exclusive) the search values'); ?></option>
        <option
            value="not_between_inclusive_querystring" <?php if ($filterSelection == 'not_between_inclusive_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?> class="sbs_plp_searchValue"><?php echo t('is not between (inclusive) the search values'); ?></option>
        <option
            value="not_between_exclusive_querystring" <?php if ($filterSelection == 'not_between_exclusive_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?> class="sbs_plp_searchValue"><?php echo t('is not between (exclusive) the search values'); ?></option>
        <option
            value="querystring_all" <?php if ($filterSelection == 'querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('matches search value'); ?></option>
        <option
            value="not_querystring_all" <?php if ($filterSelection == 'not_querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('does not match search value'); ?></option>
    <?php } ?>
</select>
<div class="pageAttributeAdditionalValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, ['not_empty', 'is_empty', 'matches_all', 'querystring_all', 'not_matches_all', 'not_querystring_all', 'less_than_match', 'less_than_querystring', 'less_than_or_equal_to_match', 'less_than_or_equal_to_querystring', 'more_than_match', 'more_than_querystring', 'more_than_or_equal_to_match', 'more_than_or_equal_to_querystring', 'between_inclusive_querystring', 'not_between_inclusive_querystring', 'between_exclusive_querystring', 'not_between_exclusive_querystring']) ? "display:none;" : ""); ?>">
    <input name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][val1]"
           value="<?php echo $values[0]; ?>" style="width:75px;"><span
        class="pageAttributeAdditionalValueSelectionSecondary"
        style="<?php echo(in_array($filterSelection, ['less_than', 'less_than_or_equal_to', 'more_than', 'more_than_or_equal_to']) ? "display:none;" : ""); ?>"> <label
            style="float:none;display:inline;"><?php echo t('and'); ?></label> <input
            name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][val2]" style="width:75px;"
            value="<?php echo $values[1]; ?>"></span>
</div>
<div class="pageAttributeDefaultValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, [$values[0], 'querystring_any', 'querystring_all', 'not_querystring_all', 'less_than_querystring', 'less_than_or_equal_to_querystring', 'more_than_querystring', 'more_than_or_equal_to_querystring', 'between_inclusive_querystring', 'not_between_inclusive_querystring', 'between_exclusive_querystring', 'not_between_exclusive_querystring']) ? "" : "display:none;"); ?>">
    <input name="searchDefaults[<?php echo $pageAttributeKeyID; ?>][]" style="width:75px;"
           value="<?php echo (is_array($values[2]) && isset($values[2][0])) ? $values[2][0] : $values[2]; ?>"
           placeholder="<?php echo t('Default Search Value'); ?>">
    <span class="pageAttributeDefaultValueSelectionSecondary"
          style="<?php echo(!in_array($filterSelection, ['between_inclusive_querystring', 'between_exclusive_querystring', 'not_between_inclusive_querystring', 'not_between_exclusive_querystring']) ? "display:none;" : ""); ?>"> <label
            style="float:none;display:inline;"><?php echo t('and'); ?></label> <input
            name="searchDefaults[<?php echo $pageAttributeKeyID; ?>][]" style="width:75px;"
            value="<?php echo (is_array($values[2]) && isset($values[2][1])) ? $values[2][1] : ''; ?>"></span>
</div>