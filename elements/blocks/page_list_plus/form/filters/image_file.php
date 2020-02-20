<?php  defined('C5_EXECUTE') or die("Access Denied."); ?>
<select name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][filterSelection]"
        class="pageAttributeInitialSelector form-control"
        data-additional-values="id_is_exactly id_is_not_exactly contains not_contains is_exactly is_not_exactly starts_with ends_with"
        data-default-value="">
    <option
        value="not_empty" <?php if (!$filterSelection || $filterSelection == 'not_empty') print 'selected'; ?>><?php echo t('is not empty'); ?></option>
    <option
        value="is_empty" <?php if ($filterSelection == 'is_empty') print 'selected'; ?>><?php echo t('is empty'); ?></option>
    <option
        value="id_is_exactly" <?php if ($filterSelection == 'id_is_exactly') print 'selected'; ?>><?php echo t('file ID is'); ?></option>
    <option
        value="id_is_not_exactly" <?php if ($filterSelection == 'id_is_not_exactly') print 'selected'; ?>><?php echo t('file ID is not'); ?></option>
    <option
        value="contains" <?php if ($filterSelection == 'contains') print 'selected'; ?>><?php echo t('filename contains'); ?></option>
    <option
        value="not_contains" <?php if ($filterSelection == 'not_contains') print 'selected'; ?>><?php echo t('filename does not contain'); ?></option>
    <option
        value="is_exactly" <?php if ($filterSelection == 'is_exactly') print 'selected'; ?>><?php echo t('filename is'); ?></option>
    <option
        value="is_not_exactly" <?php if ($filterSelection == 'is_not_exactly') print 'selected'; ?>><?php echo t('filename is not'); ?></option>
    <option
        value="starts_with" <?php if ($filterSelection == 'starts_with') print 'selected'; ?>><?php echo t('filename starts with'); ?></option>
    <option
        value="ends_with" <?php if ($filterSelection == 'ends_with') print 'selected'; ?>><?php echo t('filename ends with'); ?></option>
    <option
        value="matches_all" <?php if ($filterSelection == 'matches_all') print 'selected'; ?>><?php echo t('file matches current page'); ?></option>
    <option
        value="not_matches_all" <?php if ($filterSelection == 'not_matches_all') print 'selected'; ?>><?php echo t('file does not match current page'); ?></option>
</select>
<div class="pageAttributeAdditionalValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, [$values[0], 'not_empty', 'is_empty', 'matches_all', 'not_matches_all']) ? "display:none;" : ""); ?>">
    <input name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][val1]"
           value="<?php echo $values[0]; ?>" placeholder="Match Value" class="form-control">
</div>
<div class="pageAttributeDefaultValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, [$values[0], 'querystring_any', 'querystring_all', 'not_querystring_all', 'less_than_querystring', 'less_than_or_equal_to_querystring', 'more_than_querystring', 'more_than_or_equal_to_querystring']) ? "" : "display:none;"); ?>">
    <input name="searchDefaults[<?php echo $pageAttributeKeyID; ?>]" value="<?php echo $values[2]; ?>"
           placeholder="<?php echo t('Default Search Value'); ?>" class="form-control">
</div>
