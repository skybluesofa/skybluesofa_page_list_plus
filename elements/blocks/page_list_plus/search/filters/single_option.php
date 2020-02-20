<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$defaultValue = '';
if (isset($_GET[$filter->getAttributeKeyHandle()]) && !empty($_GET[$filter->getAttributeKeyHandle()])) {
    if (!is_array($_GET[$filter->getAttributeKeyHandle()])) {
        $defaultValue = [$_GET[$filter->getAttributeKeyHandle()]];
    } else {
        $defaultValue = $_GET[$filter->getAttributeKeyHandle()];
    }
} elseif (isset($controller->searchDefaults[$filter->getAttributeKeyID()])) {
    if (!is_array($controller->searchDefaults[$filter->getAttributeKeyID()])) {
        $defaultValue = [$controller->searchDefaults[$filter->getAttributeKeyID()]];
    } else {
        $defaultValue = $controller->searchDefaults[$filter->getAttributeKeyID()];
    }
}
$showRange = false;
$pageAttributesUsedForFilter = $controller->pageAttributesUsedForFilter;
if (is_array($pageAttributesUsedForFilter) && isset($pageAttributesUsedForFilter[$filter->getAttributeKeyID()]['filterSelection'])) {
    $rangeAttributes = ['between_inclusive_querystring', 'between_exclusive_querystring', 'not_between_inclusive_querystring', 'not_between_exclusive_querystring'];
    if (in_array($pageAttributesUsedForFilter[$filter->getAttributeKeyID()]['filterSelection'], $rangeAttributes)) {
        $showRange = true;
    }
}
if ($showRange) { ?>
    <span class="plp_<?php echo $filter->getAttributeTypeHandle(); ?>_range">
<input name="<?php echo $filter->getAttributeKeyHandle(); ?>[0]" type="text"
       class="plp_<?php echo $filter->getAttributeTypeHandle(); ?>" value="<?php echo $defaultValue[0]; ?>"> to
<input name="<?php echo $filter->getAttributeKeyHandle(); ?>[1]" type="text"
       class="plp_<?php echo $filter->getAttributeTypeHandle(); ?>" value="<?php echo $defaultValue[1]; ?>"></span>
<?php } else { ?>
    <input name="<?php echo $filter->getAttributeKeyHandle(); ?>" type="text"
           class="plp_<?php echo $filter->getAttributeTypeHandle(); ?>" value="<?php echo $defaultValue[0]; ?>">
<?php }
