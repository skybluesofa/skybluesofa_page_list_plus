<?php 
defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Attribute\Topics\Controller as TopicsAttributeTypeController;
use Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Core\Tree\Type\Topic as TopicTree;
use Concrete\Core\Tree\Node\Node as TreeNode;

$showAsDropdown = false;
if (isset($controller->pageAttributesUsedForFilter[$filter->getAttributeKeyID()])) {
    if (in_array($controller->pageAttributesUsedForFilter[$filter->getAttributeKeyID()]['filterSelection'], ['querystring_all', 'not_querystring_all'])) {
        $showAsDropdown = true;
    }
}

if ($showAsDropdown) {
    $options = [];
    $ak = CollectionAttributeKey::getByHandle($filter->getAttributeKeyHandle());
    $cnt = $ak->getController();
    $treeId = $cnt->getTopicTreeID();
    $tree = TopicTree::getByID($treeId);
    $root = $tree->getRootTreeNodeObject();
    $childNodeIds = $root->getAllChildNodeIDs();
    $nodePaths = [];
    foreach ($childNodeIds as $childNodeId) {
        $node = TreeNode::getByID($childNodeId);
        //$nodePath = $node->getTreeNodeDisplayName();
        $nodePath = substr($node->getTreeNodeDisplayPath('text'), 1);
        $options[$nodePath] = $nodePath;
    }
    if ($controller->alphabetizeSearchSelects) {
        natcasesort($options);
    }
    $getValue = [];
    if (isset($controller->searchDefaults[$filter->getAttributeKeyID()]) && !empty($controller->searchDefaults[$filter->getAttributeKeyID()])) {
        $getValue[] = strtolower($controller->searchDefaults[$filter->getAttributeKeyID()]);
    }
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
                if (in_array(strtolower($value), $getValue)) {
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
                    if (in_array(strtolower($value), $getValue)) {
                        echo 'checked="checked"';
                    }
                    ?>><?php echo $title; ?></span>
                <?php 
                $i++;
            } ?>
        </div>
    <?php } ?>
<?php } else { ?>
    <?php 
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
    $controllerAttributes = $controller->attributes;
    if (is_array($controllerAttributes) && isset($controllerAttributes[$filter->getAttributeKeyID()]['eval'])) {
        $rangeAttributes = ['between_inclusive_querystring', 'between_exclusive_querystring', 'not_between_inclusive_querystring', 'not_between_exclusive_querystring'];
        if (in_array($controllerAttributes[$filter->getAttributeKeyID()]['eval'], $rangeAttributes)) {
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
    <?php } ?>
<?php } ?>