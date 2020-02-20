<?php 
namespace Concrete\Package\SkybluesofaPageListPlus;

use Concrete\Core\Controller\Controller;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;
use Concrete\Package\SkybluesofaPageListPlus\Block\PageListPlus\Controller as PageListPlusBlockController;
use Concrete\Core\Block\Block;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Page;
use Concrete\Core\Block\View\BlockView;
use Concrete\Core\Block\View\BlockViewTemplate;

defined('C5_EXECUTE') or die("Access Denied.");

class Reload extends Controller
{

    static function render()
    {
        if (!isset($_REQUEST['bID'])) {
            return;
        }
        $blockID = str_replace('block_','',strtolower($_REQUEST['bID']));
        $block = Block::getByID($blockID);
        $page = Page::getByID($_REQUEST['cID']);
        $req = Request::getInstance();
        $req->setCurrentPage($page);

        $controller = new PageListPlusBlockController($block);
        $controller->useForSearch = $_REQUEST['query'] ? true : false;

        $controller->setupView();

        $bv = new BlockView($block);
        $bv->render('view');
    }
}