<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$pkg = Package::getByHandle('skybluesofa_page_list_plus');
$jsDateFormat = $pkg->getConfig()->get('date_picker.format','mm/dd/yy');
$phpDateFormat = $pkg->getConfig()->get('date_picker.format','m/d/Y');
$defaultValue = '';
if (isset($_GET[$filter->getAttributeKeyHandle()]) && strlen($_GET[$filter->getAttributeKeyHandle()])) {
    $defaultValue = $_GET[$filter->getAttributeKeyHandle()];
} elseif (isset($controller->searchDefaults[$filter->getAttributeKeyID()])) {
    $defaultValue = $controller->searchDefaults[$filter->getAttributeKeyID()];
}

$defaultValue = $defaultValue ? date($phpDateFormat, strtotime($defaultValue)) : '';
?>
<span class="ccm-input-date-wrapper" id="' . $id . '_dw">
	<input name="<?php echo $filter->getAttributeKeyHandle(); ?>"
           id="<?php echo $filter->getAttributeKeyHandle() . '_' . $controller->cID; ?>"
           type="text" class="ccm-input-date" value="<?php echo $defaultValue; ?>">
</span>
<style>#ui-datepicker-div {
        z-index: 10 !important;
    }</style>
<script type="text/javascript">$(function () {
        $("#<?php   echo $filter->getAttributeKeyHandle().'_'.$controller->cID; ?>").datepicker({
            dateFormat: '<?php   echo $jsDateFormat; ?>',
            changeYear: true,
            showAnim: 'fadeIn'
        });
    });</script>
