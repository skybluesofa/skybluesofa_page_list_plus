<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<select name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][filterSelection]"
        class="pageAttributeInitialSelector form-control"
        data-additional-values="less_than less_than_or_equal_to more_than more_than_or_equal_to between_inclusive between_exclusive not_between_inclusive not_between_exclusive"
        data-additional-values-secondary="between_inclusive between_exclusive not_between_inclusive not_between_exclusive"
        data-default-value="less_than_querystring less_than_or_equal_to_querystring more_than_querystring more_than_or_equal_to_querystring querystring_all not_querystring_all"
        data-default-value-secondary="between_inclusive between_exclusive not_between_inclusive not_between_exclusive">
    <option
        value="not_empty" <?php if ($filterSelection == 'not_empty') print 'selected'; ?>><?php echo t('is rated'); ?></option>
    <option
        value="is_empty" <?php if ($filterSelection == 'is_empty') print 'selected'; ?>><?php echo t('is not rated'); ?></option>
    <option
        value="less_than" <?php if ($filterSelection == 'less_than') print 'selected'; ?>><?php echo t('is less than'); ?></option>
    <option
        value="less_than_match" <?php if ($filterSelection == 'less_than_match') print 'selected'; ?>><?php echo t('is less than current page'); ?></option>
    <option
        value="less_than_or_equal_to" <?php if ($filterSelection == 'less_than_or_equal_to') print 'selected'; ?>><?php echo t('is less than or equal to'); ?></option>
    <option
        value="less_than_or_equal_to_match" <?php if ($filterSelection == 'less_than_or_equal_to_match') print 'selected'; ?>><?php echo t('is less than or equal to current page'); ?></option>
    <option
        value="more_than" <?php if ($filterSelection == 'more_than') print 'selected'; ?>><?php echo t('is more than'); ?></option>
    <option
        value="more_than_match" <?php if ($filterSelection == 'more_than_match') print 'selected'; ?>><?php echo t('is more than current page'); ?></option>
    <option
        value="more_than_or_equal_to" <?php if ($filterSelection == 'more_than_or_equal_to') print 'selected'; ?>><?php echo t('is more than or equal to'); ?></option>
    <option
        value="more_than_or_equal_to_match" <?php if ($filterSelection == 'more_than_or_equal_to_match') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
        class="sbs_plp_searchValue"><?php echo t('is more than or equal to current page'); ?></option>
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
    <?php if (!$disallowSearch) { ?>
        <option
            value="less_than_querystring" <?php if ($filterSelection == 'less_than_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is less than search value'); ?></option>
        <option
            value="less_than_or_equal_to_querystring" <?php if ($filterSelection == 'less_than_or_equal_to_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is less than or equal to search value'); ?></option>
        <option
            value="more_than_querystring" <?php if ($filterSelection == 'more_than_querystring') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('is more than search value'); ?></option>
        <option
            value="more_than_or_equal_to_querystring" <?php if ($filterSelection == 'more_than_or_equal_to_querystring') print 'selected'; ?>><?php echo t('is more than or equal to search value'); ?></option>
        <option
            value="not_matches_all" <?php if ($filterSelection == 'not_matches_all') print 'selected'; ?>><?php echo t('does not match current page'); ?></option>
        <option
            value="querystring_all" <?php if ($filterSelection == 'querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('matches search value'); ?></option>
        <option
            value="not_querystring_all" <?php if ($filterSelection == 'not_querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('does not match search value'); ?></option>
    <?php } ?>
</select>
<div class="pageAttributeAdditionalValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, ['not_empty', 'is_empty', 'matches_all', 'querystring_all', 'not_matches_all', 'not_querystring_all', 'less_than_match', 'less_than_querystring', 'less_than_or_equal_to_match', 'less_than_or_equal_to_querystring', 'more_than_match', 'more_than_querystring', 'more_than_or_equal_to_match', 'more_than_or_equal_to_querystring']) ? "display:none;" : ""); ?>">
    <input name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][val1]" style="width:75px;"
           value="<?php echo $values[0]; ?>"><span class="pageAttributeAdditionalValueSelectionSecondary"
                                                   style="<?php echo(in_array($filterSelection, ['less_than', 'less_than_or_equal_to', 'more_than', 'more_than_or_equal_to']) ? "display:none;" : ""); ?>"> <label
            style="float:none;display:inline;"><?php echo t('and'); ?></label> <input
            name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][val2]" style="width:75px;"
            value="<?php echo $values[1]; ?>"></span>
</div>
<div class="pageAttributeDefaultValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, [$values[0], 'querystring_any', 'querystring_all', 'not_querystring_all', 'less_than_querystring', 'less_than_or_equal_to_querystring', 'more_than_querystring', 'more_than_or_equal_to_querystring']) ? "" : "display:none;"); ?>">
    <input name="searchDefaults[<?php echo $pageAttributeKeyID; ?>]" value="<?php echo $values[2]; ?>"
           placeholder="<?php echo t('Default Search Value'); ?>" class="form-control">
</div>