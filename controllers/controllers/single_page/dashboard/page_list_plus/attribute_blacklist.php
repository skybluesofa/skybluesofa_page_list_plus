<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\Controller\SinglePage\Dashboard\PageListPlus;

use Concrete\Core\Validation\CSRF\Token as ValidationToken;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Attribute\Key\CollectionKey;
use Database;

defined('C5_EXECUTE') or die("Access Denied.");

class AttributeBlacklist extends DashboardPageController
{

    var $helpers = ['form'];

    public function on_start()
    {
        $this->token = new ValidationToken();
    }

    public function view()
    {
        $this->loadBlacklist();
    }

    public function loadBlacklist()
    {
        $pageAttributes = CollectionKey::getList();
        uasort($pageAttributes, function ($a, $b) {
            if ($a->getAttributeKeyName() == $b->getAttributeKeyName()) {
                return 0;
            }
            return ($a->getAttributeKeyName() < $b->getAttributeKeyName()) ? -1 : 1;
        });
        $this->set('pageAttributes', $pageAttributes);

        $db = Database::getActiveConnection();
        $q = $db->Execute("SELECT * FROM aoPageListPlusAttributeBlacklist");
        $blacklist = [];
        while ($r = $q->FetchRow()) {
            $blacklist[$r['akHandle']] = $r['blacklist'];
        }
        $this->set('blacklist', $blacklist);
    }

    public function update_blacklist()
    {
        if ($this->token->validate("update_blacklist")) {
            if ($this->isPost()) {
                if (isset($_POST['blacklist'])) {
                    $db = Database::getActiveConnection();
                    $pageAttributes = CollectionKey::getList();

                    foreach ($pageAttributes as $pageAttribute) {
                        if (!in_array($pageAttribute->getAttributeKeyID(), $_POST['blacklist'])) {
                            $db->Execute("DELETE FROM aoPageListPlusAttributeBlacklist WHERE akHandle=?", [$pageAttribute->getAttributeKeyHandle()]);
                        } else {
                            $q = $db->Execute("SELECT akHandle FROM aoPageListPlusAttributeBlacklist WHERE akHandle=?", [$pageAttribute->getAttributeKeyHandle()]);
                            if ($q->FetchRow()) {
                                $db->Execute("UPDATE aoPageListPlusAttributeBlacklist SET blacklist=? WHERE akHandle=?", [1, $pageAttribute->getAttributeKeyHandle()]);
                            } else {
                                $db->Execute("INSERT INTO aoPageListPlusAttributeBlacklist (blacklist, akHandle) VALUES (?, ?)", [1, $pageAttribute->getAttributeKeyHandle()]);
                            }
                        }
                    }
                }
                $this->set('message', t('Page List Attribute blacklist has been updated.'));
            }
        } else {
            $this->set('error', [$this->token->getErrorMessage()]);
        }
        $this->loadBlacklist();
    }

}