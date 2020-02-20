<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\Controller\SinglePage\Dashboard;

use \Concrete\Core\Page\Controller\DashboardPageController;

defined('C5_EXECUTE') or die("Access Denied.");

class PageListPlus extends DashboardPageController
{

    public function view()
    {
        $this->redirect('/dashboard/page_list_plus/attribute_blacklist');
    }
}