<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter;

use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Filter;
use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\PageListPlus;
use Concrete\Core\File\FileList;

defined('C5_EXECUTE') or die("Access Denied.");

class ImageFile extends Contract\FilterContract
{
    public function run()
    {
        if ($this->currentAttributeFilter['filterSelection'] == 'not_empty') {
            $this->pageListPlus->filterByClause("/* image/file */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . "!=0)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_empty') {
            $this->pageListPlus->filterByClause("/* image/file */ (" . $this->currentAttributeFilter['handle'] . "='' OR  " . $this->currentAttributeFilter['handle'] . "=0)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'id_is_exactly') {
            $this->pageListPlus->filterByClause("/* image/file */ (fID='" . $this->currentAttributeFilter['val1'] . "')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'id_is_not_exactly') {
            $this->pageListPlus->filterByClause("/* image/file */ (fID!='" . $this->currentAttributeFilter['val1'] . "')");
        } else {
            // more intensive db stuff
            $fl = new FileList();
            if ($this->currentAttributeFilter['filterSelection'] == 'contains') {
                $fl->filter('fvFilename', "%" . $this->currentAttributeFilter['val1'] . "%", $comparison = 'LIKE');
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_contains') {
                // do a negative search, it should return less files
                $fl->filter('fvFilename', "%" . $this->currentAttributeFilter['val1'] . "%", $comparison = 'LIKE');
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_exactly') {
                $fl->filter('fvFilename', $this->currentAttributeFilter['val1'], $comparison = 'LIKE');
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'starts_with') {
                $fl->filter('fvFilename', $this->currentAttributeFilter['val1'] . "%", $comparison = 'LIKE');
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'ends_with') {
                $fl->filter('fvFilename', "%" . $this->currentAttributeFilter['val1'], $comparison = 'LIKE');
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'matches_all') {
                $fl->filter('fvFilename', $this->currentAttributeFilter['val1'], $comparison = 'LIKE');
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_matches_all') {
                // do a negative search, it should return less files
                $fl->filter('fvFilename', $this->currentAttributeFilter['val1'], $comparison = 'LIKE');
            }
            $fl->setItemsPerPage(1000000);
            $files = $fl->get();
            $filter_file_ids = [];
            foreach ($files as $file) {
                $filter_file_ids[] = $file->getFileID();
            }
            if (count($filter_file_ids)) {
                if ($this->currentAttributeFilter['filterSelection'] == 'not_matches_all' || $this->currentAttributeFilter['filterSelection'] == 'not_contains') {
                    $this->pageListPlus->filterByClause("/* image/file */ (" . $this->currentAttributeFilter['handle'] . " NOT IN (" . implode(',', $filter_file_ids) . "))");
                } else {
                    $this->pageListPlus->filterByClause("/* image/file */ (" . $this->currentAttributeFilter['handle'] . " IN (" . implode(',', $filter_file_ids) . "))");
                }
            } else {
                if ($this->currentAttributeFilter['filterSelection'] == 'not_matches_all' || $this->currentAttributeFilter['filterSelection'] == 'not_contains') {
                    $this->pageListPlus->filterByClause("/* image/file */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . "!=0)");
                } else {
                    $this->pageListPlus->filterByClause("/* image/file */ (1!=1)");
                }
            }
        }
    }
}
