<?php  defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Concrete\Attribute\Select\Controller as SelectAttributeTypeController;
use Concrete\Core\Attribute\Type as AttributeType;

$ak = CollectionAttributeKey::getByHandle($pageAttribute->getAttributeKeyHandle());
$em = \Database::connection()->getEntityManager();
$satc = new \Concrete\Attribute\Select\Controller($em);
$satc->setAttributeKey($ak);
$selectOptions = $satc->getOptions();
?>
<select name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][filterSelection]"
        class="pageAttributeInitialSelector form-control"
        data-additional-values="equals not_equals in_list in_list_all not_in_list"
        data-additional-values-multiple="in_list in_list_all not_in_list"
        data-default-value="querystring_any querystring_all not_querystring_all">
    <option
        value="not_empty" <?php if ($filterSelection == 'not_empty') print 'selected'; ?>><?php echo t('is not empty'); ?></option>
    <option
        value="is_empty" <?php if ($filterSelection == 'is_empty') print 'selected'; ?>><?php echo t('is empty'); ?></option>
    <option value="equals" <?php if ($filterSelection == 'equals') print 'selected'; ?>><?php echo t('is'); ?></option>
    <option
        value="not_equals" <?php if ($filterSelection == 'not_equals') print 'selected'; ?>><?php echo t('is not'); ?></option>
    <option value="in_list" <?php if ($filterSelection == 'in_list') print 'selected'; ?>><?php echo t('matches any selected'); ?></option>
    <option value="in_list_all" <?php if ($filterSelection == 'in_list_all') print 'selected'; ?>><?php echo t('matches all selected'); ?></option>
    <option value="not_in_list" <?php if ($filterSelection == 'not_in_list') print 'selected'; ?>><?php echo t('does not match any selected'); ?></option>
    <option
        value="matches_any" <?php if ($filterSelection == 'matches_any') print 'selected'; ?>><?php echo t('matches any from current page'); ?></option>
    <option
        value="matches_all" <?php if ($filterSelection == 'matches_all') print 'selected'; ?>><?php echo t('matches all from current page'); ?></option>
    <option
        value="not_matches_all" <?php if ($filterSelection == 'not_matches_all') print 'selected'; ?>><?php echo t('does not match anything from current page'); ?></option>
    <?php if (!$disallowSearch) { ?>
        <option
            value="querystring_any" <?php if ($filterSelection == 'querystring_any') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('matches any from search value'); ?></option>
        <option
            value="querystring_all" <?php if ($filterSelection == 'querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('matches all from search value'); ?></option>
        <option
            value="not_querystring_all" <?php if ($filterSelection == 'not_querystring_all') print 'selected'; ?> <?php if (!$controller->userForSearch) print 'disabled="disabled"'; ?>
            class="sbs_plp_searchValue"><?php echo t('does not match anything from search value'); ?></option>
    <?php } ?>
</select>
<div class="pageAttributeAdditionalValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, ['not_empty', 'is_empty', 'matches_all', 'querystring_all', 'matches_any', 'querystring_any', 'not_matches_all', 'not_querystring_all']) ? "display:none;" : ""); ?>">
    <select name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][val1]" class="form-control" multiple="multiple" size="">
        <?php 
        if (count($selectOptions) == 0) {
            ?>
            <option value=""> - <?php echo t('No options are currently available'); ?> -</option>
        <?php 
        } else {
            $options = [];
            foreach ($selectOptions as $selectOption) :
                $optionValue = $selectOption->getSelectAttributeOptionValue();
                trim($optionValue);
                if (!empty($optionValue)) :
                    $options[] = $optionValue;
                endif;
            endforeach;
            natcasesort($options);
            foreach ($options as $option) :
                ?>
                <option
                    value="<?php echo $option; ?>" <?php if ($values[0] == $option) print 'selected'; ?>><?php echo $option; ?></option>
            <?php 
            endforeach;
        }
        ?>
    </select>
</div>
<div class="pageAttributeDefaultValueSelection"
     style="margin-top:5px;<?php echo(in_array($filterSelection, [$values[0], 'querystring_any', 'querystring_all', 'not_querystring_all', 'less_than_querystring', 'less_than_or_equal_to_querystring', 'more_than_querystring', 'more_than_or_equal_to_querystring']) ? "" : "display:none;"); ?>">
    <select name="searchDefaults[<?php echo $pageAttributeKeyID; ?>]" class="form-control">
        <?php 
        if (count($selectOptions) == 0) {
            ?>
            <option value=""> - <?php echo t('No options are currently available'); ?> -</option>
        <?php 
        } else {
            $options = [''];
            foreach ($selectOptions as $selectOption) :
                $optionValue = $selectOption->getSelectAttributeOptionValue();
                trim($optionValue);
                if (!empty($optionValue)) :
                    $options[] = $optionValue;
                endif;
            endforeach;
            natcasesort($options);
            foreach ($options as $option) :
                ?>
                <option
                    value="<?php echo $option; ?>" <?php if ($values[2] == $option) print 'selected'; ?>><?php echo $option; ?></option>
            <?php 
            endforeach;
        }
        ?>
    </select>
</div>
