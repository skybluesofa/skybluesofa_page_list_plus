<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\Controller\SinglePage\Dashboard\PageListPlus;

use Concrete\Core\Validation\CSRF\Token as ValidationToken;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Attribute\Key\CollectionKey;
use Concrete\Core\Area\Area;
use Database;

defined('C5_EXECUTE') or die("Access Denied.");

class AreaBlacklist extends DashboardPageController
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
        $areaObject = new Area('Main');
        $areas = $areaObject->getHandleList();
        sort($areas);

        $this->set('pageAreas', $areas);

        $db = Database::getActiveConnection();
        $q = $db->Execute("SELECT * FROM aoPageListPlusAreaBlacklist");
        $blacklist = [];
        while ($r = $q->FetchRow()) {
            $blacklist[$r['area']] = $r['area'];
        }
        $this->set('blacklist', $blacklist);
    }

    public function update_blacklist()
    {
        if ($this->token->validate("update_blacklist")) {
            if ($this->isPost()) {
                if (isset($_POST['blacklist'])) {
                    $db = Database::getActiveConnection();
                    $areaObject = new Area('Main');
                    $areas = $areaObject->getHandleList();

                    foreach ($areas as $area) {
                        if (!in_array($area, $_POST['blacklist'])) {
                            $db->Execute("DELETE FROM aoPageListPlusAreaBlacklist WHERE area=?", [$area]);
                        } else {
                            $q = $db->Execute("SELECT area FROM aoPageListPlusAreaBlacklist WHERE area=?", [$area]);
                            if ($q->FetchRow()) {
                                $db->Execute("UPDATE aoPageListPlusAreaBlacklist SET blacklist=? WHERE area=?", [1, $area]);
                            } else {
                                $db->Execute("INSERT INTO aoPageListPlusAreaBlacklist (blacklist, area) VALUES (?, ?)", [1, $area]);
                            }
                        }
                    }
                }
                $this->set('message', t('Page List+ Area blacklist has been updated.'));
            }
        } else {
            $this->set('error', [$this->token->getErrorMessage()]);
        }
        $this->loadBlacklist();
    }

}