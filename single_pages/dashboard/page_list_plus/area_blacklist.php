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
echo $cdh->getDashboardPaneHeaderWrapper(t('Page List+ Page Area Blacklist'), t('Exclude page areas from Page List+'), false, false); ?>
<form method="post" id="skybluesofa_page_list_plus_update_blacklist_form"
      action="<?php echo $this->url('/dashboard/page_list_plus/area_blacklist', 'update_blacklist'); ?>">
    <?php echo $vt->output('update_blacklist'); ?>
    <input name="section" value="update_blacklist" type="hidden">

    <div class="ccm-dashboard-content">
        <table style="width:100%;">
            <tr>
                <?php 
                $pageAreas = array_chunk($pageAreas, ceil(count($pageAreas) / 2));
                foreach ($pageAreas as $column) {
                    ?>
                    <td>
                        <?php  foreach ($column as $pageArea) { ?>
                            <label for="blacklistArea<?php echo $pageArea; ?>"><input name="blacklist[]"
                                value="<?php echo $pageArea; ?>"
                                id="blacklistArea<?php echo $pageArea; ?>"
                                type="checkbox"
                                style="display:inline;" <?php if (array_key_exists($pageArea, $blacklist)) echo 'checked'; ?>> <?php echo $pageArea; ?>
                            </label><br>
                        <?php } ?>
                    </td>
                <?php } ?>
            </tr>
        </table>

        <div style="clear:both;height:1px;"></div>
        <div class="well">
            <?php echo t('Selected Page Areas will not show in the Page List+ block dialog. You will not be able to filter content based on these areas.'); ?>
        </div>
    </div>
    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <button class="pull-right btn btn-success" type="submit"><?php echo t('Save Blacklist Values') ?></button>
        </div>
    </div>
</form>
