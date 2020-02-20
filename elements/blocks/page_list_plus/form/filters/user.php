<?php  defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Support\Facade\Facade;
use Concrete\Core\Form\Service\Widget\UserSelector;

$app = Facade::getFacadeApplication();
?>
<select name="pageAttributesUsedForFilter[<?php echo $pageAttributeKeyID; ?>][filterSelection]"
        class="pageAttributeInitialSelector form-control"
        data-additional-values="equals not_equals"
        data-default-value="">
    <option value="equals" <?php if ($filterSelection == 'equals') print 'selected'; ?>><?php echo t('is'); ?></option>
    <option
        value="not_equals" <?php if ($filterSelection == 'not_equals') print 'selected'; ?>><?php echo t('is not'); ?></option>
    <option
        value="matches_current" <?php if ($filterSelection == 'matches_current') print 'selected'; ?>><?php echo t('matches current user'); ?></option>
    <option
        value="not_matches_current" <?php if ($filterSelection == 'not_matches_current') print 'selected'; ?>><?php echo t('does not match current user'); ?></option>
    <option
        value="matches_all" <?php if ($filterSelection == 'matches_all') print 'selected'; ?>><?php echo t('matches current page'); ?></option>
    <option
        value="not_matches_all" <?php if ($filterSelection == 'not_matches_all') print 'selected'; ?>><?php echo t('does not match current page'); ?></option>
</select>
<div class="pageAttributeAdditionalValueSelection" id="userPageAttribute_<?php echo $pageAttributeKeyID; ?>"
     style="<?php echo(in_array($filterSelection, ['equals', 'not_equals']) ? "" : "display:none;"); ?>">
    <?php 
    $userSelector = $app->make(UserSelector::class);
    echo $userSelector->selectUser('pageAttributesUsedForFilter[' . $pageAttributeKeyID . '][val1]', $values[0] ? $values[0] : false);
    ?>
    <script>
        $(function () {
            $('#userPageAttribute_<?php echo $pageAttributeKeyID; ?> .ccm-sitemap-select-item').on('click', function () {
                var selector = $(this);
                ConcreteEvent.subscribe('UserSearchDialogAfterSelectUser.core', function (e, data) {
                    selector.change();
                });
            });
        });
    </script>
</div>
