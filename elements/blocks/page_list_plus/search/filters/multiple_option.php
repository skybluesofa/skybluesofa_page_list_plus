<?php 
defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Attribute\Select\Controller as SelectAttributeTypeController;
use Concrete\Core\Attribute\Type as AttributeType;

$options = [];
if ($filter->getAttributeTypeHandle() == "select") {
    Loader::model('attribute/type');
    $satc = Core::make('Concrete\Attribute\Select\Controller');
    $satc->setAttributeKey(CollectionAttributeKey::getByHandle($filter->getAttributeKeyHandle()));
    $values = $satc->getOptions();
    foreach ($values as $v) {
        $optionValue = $v->value;
        trim($optionValue);
        if (strlen($optionValue))
            $options[$optionValue] = $optionValue;
    }
    if ($controller->alphabetizeSearchSelects) {
        natcasesort($options);
    }
} else {
    $options['false'] = 'False';
    $options['true'] = 'True';
}
$getValue = [];
if (isset($controller->searchDefaults[$filter->getAttributeKeyID()]) && !empty($controller->searchDefaults[$filter->getAttributeKeyID()])) {
    $getValue[] = $controller->searchDefaults[$filter->getAttributeKeyID()];
}
$request = Request::getInstance();
if (isset($_GET[$filter->getAttributeKeyHandle()])) {
    $getValue = [];
    if (is_array($_GET[$filter->getAttributeKeyHandle()])) {
        foreach ($_GET[$filter->getAttributeKeyHandle()] as $get) {
            $getValue[] = strtolower($get);
        }
    } else {
        $getValue[] = strtolower($_GET[$filter->getAttributeKeyHandle()]);
    }
}
$showSearchSelectAsCheckboxAttributes = $controller->showSearchSelectAsCheckboxAttributes;
if (!$controller->showSearchSelectsAsCheckbox || ($controller->showSearchSelectsAsCheckbox && !in_array($filter->getAttributeKeyID(), $showSearchSelectAsCheckboxAttributes))) {
    $options = array_reverse($options, true);
    $options[''] = $controller->nameAsSearchFilterAllText ? $filter->getAttributeKeyName() : t($controller->searchFilterAllText);
    $options = array_reverse($options, true);
    ?>
    <select name="<?php echo $filter->getAttributeKeyHandle(); ?>"
            class="plp_<?php echo $filter->getAttributeKeyHandle(); ?>">
        <?php  foreach ($options as $value => $title) { ?>
            <option value="<?php echo $value; ?>"<?php 
            if (in_array($value, $getValue)) {
                echo 'selected="selected"';
            }
            ?>><?php echo $title; ?></option>
        <?php } ?>
    </select>
<?php } else {
    $i = 0;
    ?>
    <div class="sbs_plp_selectboxReplacement"
         id="sbs_plp_selectboxReplacement_<?php echo $filter->getAttributeKeyHandle(); ?>">
        <?php  foreach ($options as $value => $title) {
            ?>
            <span>
				<input
                    type="checkbox"
                    name="<?php echo $filter->getAttributeKeyHandle(); ?>[<?php echo $i; ?>]"
                    class="plp_<?php echo $filter->getAttributeTypeHandle(); ?>"
                    value="<?php echo $value; ?>" <?php 
                if (in_array($value, $getValue)) {
                    echo 'checked="checked"';
                }
                ?>><?php echo $title; ?></span>
            <?php 
            $i++;
        }
        ?>
    </div>
<?php }