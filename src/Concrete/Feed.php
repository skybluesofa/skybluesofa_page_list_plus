<?php 
namespace Concrete\Package\SkybluesofaPageListPlus;

use Database;
use Concrete\Core\Page\Collection\Collection;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Feed as PageFeed;
use Concrete\Controller\Feed as CoreFeed;

use Request;
use Concrete\Core\Block\Block;
use Concrete\Core\Block\BlockType\BlockType;
use URL;

class Feed extends CoreFeed
{

    private $pages = [];
    private $pageListPlus = null;

    public function get($identifier=null, $defaultValue=null)
    {
        $feed = PageFeed::getByHandle($identifier);
        if (!is_object($feed)) {
            exit;
        }
        $pageListPlusBlockSettings = self::getPageListPlusBlockWithFeedId($feed->getID());
        if ($pageListPlusBlockSettings) {
            $block = Block::getByID($pageListPlusBlockSettings['bID']);
            $bt = BlockType::getByHandle('page_list_plus');
            $class = $bt->getBlockTypeClass();
            if ($class !== false) {
                $block->instance = new $class($block);
                if (!$block->isBlockInStack()) {
                    $request = Request::getInstance();
                    $request->setCurrentPage(Page::getByID($block->instance->getCollectionObject()->getCollectionID()));
                }
            }
            $this->pageListPlus = PageListPlus::generate($pageListPlusBlockSettings);
            $this->pageListPlus->debug(false);
            $this->pageListPlus->setItemsPerPage($this->pageListPlus->numberOfResults>0?$this->pageListPlus->numberOfResults:10000);
            $this->pagination = $this->pageListPlus->getPagination();
            $this->pages = $this->pagination->getCurrentPageResults();
            if (count($this->pages) > 0) {
                header('Content-Type: text/xml');
                print $this->getOutput();
            }
        } else {
            parent::get($identifier, null);
        }
        exit;
    }

    private function getPageListPlusBlockWithFeedId($pfID) {
        $db = Database::get();
        $sql = "SELECT * FROM btPageListPlus WHERE pfID=? ORDER BY bID DESC";
        $pageListPlusSettings = $db->getRow($sql, [$pfID]);
        if (isset($pageListPlusSettings['bID']) && $pageListPlusSettings['bID']) {
            $pageListPlusSettings['pageAttributesUsedForFilter'] = unserialize($pageListPlusSettings['pageAttributesUsedForFilter']);
            return $pageListPlusSettings;
        } else {
            return false;
        }
    }

    protected function getPageFeedContent(Page $p)
    {
        switch($this->pfContentToDisplay) {
            case 'S':
                return $p->getCollectionDescription();
            case 'A':
                $a = new \Area($this->getAreaHandleToDisplay());
                $blocks = $a->getAreaBlocksArray($p);
                $r = Request::getInstance();
                $r->setCurrentPage($p);
                ob_start();
                foreach($blocks as $b) {
                    $bv = new BlockView($b);
                    $bv->render('view');
                }
                $content = ob_get_contents();
                ob_end_clean();
                return $content;
        }
    }

    private function getOutput()
    {
        $writer = new \Zend\Feed\Writer\Feed();
        $writer->setTitle($this->pageListPlus->getFeedTitle());
        $writer->setDescription($this->pageListPlus->getFeedDescription());
        $link = URL::to('/');
        $writer->setLink((string) $link);
        foreach($this->pagination->getCurrentPageResults() as $p) {
            $entry = $writer->createEntry();
            $entry->setTitle($p->getCollectionName());
            $entry->setDateCreated(strtotime($p->getCollectionDatePublic()));
            $content = $this->getPageFeedContent($p);
            if (!$content) {
                $content = t('No Content.');
            }
            $entry->setDescription($content);
            $entry->setLink((string) $p->getCollectionLink(true));
            $writer->addEntry($entry);
        }

        return $writer->export('rss');
    }
}
