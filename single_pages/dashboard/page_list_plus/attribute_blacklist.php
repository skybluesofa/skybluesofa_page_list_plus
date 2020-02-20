<?php  defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Support\Facade\Facade;
use Concrete\Core\Application\Service\Urls as ConcreteUrlsHelper;
use Concrete\Core\Validation\CSRF\Token as ValidationToken;
use Concrete\Core\Form\Service\Form as FormHelper;
use Concrete\Core\Application\Service\Dashboard as ConcreteDashboardHelper;

$app = Facade::getFacadeApplication();

$uh = new ConcreteUrlsHelper();
$vt = new ValidationToken();
$form = $app->make(FormHelper::class);

$cdh = new ConcreteDashboardHelper();
echo $cdh->getDashboardPaneHeaderWrapper(t('Page List+ Attribute Blacklist'), t('Exclude page attributes from Page List+'), false, false); ?>
<form method="post" id="skybluesofa_page_list_plus_update_blacklist_form"
      action="<?php echo $this->url('/dashboard/page_list_plus/attribute_blacklist', 'update_blacklist'); ?>">
    <?php echo $vt->output('update_blacklist'); ?>
    <input name="section" value="update_blacklist" type="hidden">

    <div class="ccm-dashboard-content">
        <table style="width:100%;">
            <tr>
                <?php 
                $pageAttributes = array_chunk($pageAttributes, ceil(count($pageAttributes) / 2));
                foreach ($pageAttributes as $column) {
                    ?>
                    <td>
                        <?php  foreach ($column as $pageAttribute) { ?>
                            <label for="blacklistAttribute<?php echo $pageAttribute->getAttributeKeyID(); ?>"><input name="blacklist[]"
                                value="<?php echo $pageAttribute->getAttributeKeyID(); ?>"
                                id="blacklistAttribute<?php echo $pageAttribute->getAttributeKeyID(); ?>"
                                type="checkbox"
                                style="display:inline;" <?php if (array_key_exists($pageAttribute->getAttributeKeyHandle(), $blacklist)) echo 'checked'; ?>> <?php echo $pageAttribute->getAttributeKeyName(); ?>
                            </label><br>
                        <?php } ?>
                    </td>
                <?php } ?>
            </tr>
        </table>

        <div style="clear:both;height:1px;"></div>
        <div class="well">
            <?php echo t('Selected Page Attributes will not show in the Page List+ block dialog. You will not be able to sort or filter by the selected attributes.'); ?>
        </div>
    </div>
    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <button class="pull-right btn btn-success" type="submit"><?php echo t('Save Blacklist Values') ?></button>
        </div>
    </div>
</form>
