<?php 
namespace Concrete\Package\SkybluesofaPageListPlus;

use Concrete\Core\Controller\Controller;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;
use Concrete\Package\SkybluesofaPageListPlus\Block\PageListPlus\Controller as PageListPlusBlockController;
use Concrete\Core\Block\Block;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Page;
use User;

defined('C5_EXECUTE') or die("Access Denied.");

class Preview extends Controller
{

    static function render()
    {
        $user = new User();
        if (!$user->uID) {
            return;
        }
        if (!isset($_POST['previewUrl'])) {
            return;
        }
        if ($_POST['showDebugInformation']) ob_start();
        $pageListPlus = PageListPlus::generate($_POST);
        //$pageListPlus->debug();
        if ($pageListPlus->numberOfResults > 0) {
            $pageListPlus->setItemsPerPage($pageListPlus->numberOfResults);
            $pagination = $pageListPlus->getPagination();
            //$this->pagination = $pagination->renderDefaultView();
            $pages = $pagination->getCurrentPageResults();
        } else {
            $pages = $pageListPlus->getResults();
        }
        if ($_POST['showDebugInformation']) {
            $debugInfo = ob_get_contents();
            ob_end_clean();
        }
        if (count($pages) > 0) {
            foreach ($pages as $page) {
                echo "<b>" . $page->getCollectionName() . "</b><br>";
            }
        }
        if ($_POST['showDebugInformation'] && $debugInfo) {
            if (strpos($_POST['showDebugInformationLocation'], 'onscreen') !== false) {
                ?>
                <hr><?php echo $debugInfo; ?>
            <?php 
            }
            if (strpos($_POST['showDebugInformationLocation'], 'console') !== false) {
                ?>
                <script>console.log("<?php echo preg_replace('#[\r\n]+#', '\n',addslashes($debugInfo)); ?>");</script>
            <?php 
            }
        }
    }
}
