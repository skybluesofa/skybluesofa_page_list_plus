<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter;

use Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Contract\FilterContract;

defined('C5_EXECUTE') or die("Access Denied.");

class Topics extends FilterContract
{
    public function run()
    {
        if ($this->currentAttributeFilter['filterSelection'] == 'not_empty') {
            $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_empty') {
            $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_exactly') {
            if ($this->currentAttributeFilter['val1']) {
                $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . " LIKE '%||/" . $this->currentAttributeFilter['val1'] . "||%')");
            } else {
                $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . " IS NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_not_exactly') {
            if ($this->currentAttributeFilter['val1']) {
                $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . " IS NULL OR " . $this->currentAttributeFilter['handle'] . " NOT LIKE '" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . " NOT LIKE '%||/" . $this->currentAttributeFilter['val1'] . "||%')");
            } else {
                $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'contains' && $this->currentAttributeFilter['val1']) {
            $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '%" . $this->currentAttributeFilter['val1'] . "%')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_contains' && $this->currentAttributeFilter['val1']) {
            $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . " NOT LIKE '%" . $this->currentAttributeFilter['val1'] . "%' OR " . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'starts_with') {
            $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '%||/" . $this->currentAttributeFilter['val1'] . "%')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_starts_with') {
            if ($this->currentAttributeFilter['val1']) {
                $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . " NOT LIKE '%||/" . $this->currentAttributeFilter['val1'] . "%')");
            } else {
                $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'node_starts_with') {
            $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . " LIKE '%/" . $this->currentAttributeFilter['val1'] . "%')");
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'node_not_starts_with') {
            if ($this->currentAttributeFilter['val1']) {
                $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . " NOT LIKE '%/" . $this->currentAttributeFilter['val1'] . "%')");
            } else {
                $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'matches_all' || $this->currentAttributeFilter['filterSelection'] == 'matches_any') {
            $nodePaths = $this->getTreeNodePaths($this->currentAttributeFilter['currentValue']);
            if (count($nodePaths) > 0) {
                $clauses = [];
                foreach ($nodePaths as $nodePath) {
                    $clauses[] = "(" . $this->currentAttributeFilter['handle'] . " LIKE '%||" . $this->escape($nodePath) . "||%')";
                }
                if ($this->currentAttributeFilter['filterSelection'] == 'matches_all') {
                    $this->pageListPlus->filterByClause("/* Topics filter */ (" . implode(' AND ', $clauses) . ")");
                } else {
                    $this->pageListPlus->filterByClause("/* Topics filter */ ((" . implode(' OR ', $clauses) . ") AND (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL))");
                }
            } else {
                $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_matches_any') {
            $nodePaths = $this->getTreeNodePaths($this->currentAttributeFilter['currentValue']);
            if (count($nodePaths) > 0) {
                $clauses = [];
                foreach ($nodePaths as $nodePath) {
                    $clauses[] = "(" . $this->currentAttributeFilter['handle'] . " NOT LIKE '%||" . $this->escape($nodePath) . "||%')";
                }
                $this->pageListPlus->filterByClause("/* Topics filter */ ((" . implode(' AND ', $clauses) . ") OR (" . $this->currentAttributeFilter['handle'] . " IS NULL))");
            } else {
                $this->pageListPlus->filterByClause("/* Topics filter */ (" . $this->currentAttributeFilter['handle'] . "!='' OR " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'querystring_all' || $this->currentAttributeFilter['filterSelection'] == 'not_querystring_all') {
            $pageAttributeKeyID = $this->pageAttribute->getAttributeKeyID();
            $pageAttributeTypeHandle = $this->pageAttribute->getAttributeTypeHandle();

            if (array_key_exists($pageAttributeKeyID, $this->searchFilters)) {
                if (isset($this->searchFilters[$pageAttributeKeyID]) && !empty($this->searchFilters[$pageAttributeKeyID])) {
                    if (!is_array($this->searchFilters[$pageAttributeKeyID])) {
                        $this->searchFilters[$pageAttributeKeyID] = [$this->searchFilters[$pageAttributeKeyID]];
                    }
                    $clauseParts = [];
                    if (count($this->searchFilters[$pageAttributeKeyID]) > 0) {
                        $compare = $this->currentAttributeFilter['filterSelection'] == 'querystring_all' ? ' LIKE ' : ' NOT LIKE ';
                        foreach ($this->searchFilters[$pageAttributeKeyID] as $this->pageAttributeElement) {
                            $this->pageAttributeElement = trim($this->pageAttributeElement);
                            if ($this->pageAttributeElement) {
                                $clauseParts[] = "(" . $this->currentAttributeFilter['handle'] . $compare . "'%||/" . $this->escape($this->pageAttributeElement) . "||%')";
                            }
                        }
                        if (count($clauseParts) > 0) {
                            $concat = $this->currentAttributeFilter['filterSelection'] == 'querystring_all' ? ' AND ' : ' OR ';
                            $this->pageListPlus->filterByClause("(" . implode($concat, $clauseParts) . ")");
                        }
                    }
                }
            }
        } elseif ($this->currentAttributeFilter['filterSelection'] == 'querystring_contains' || $this->currentAttributeFilter['filterSelection'] == 'not_querystring_contains') {
            $pageAttributeKeyID = $this->pageAttribute->getAttributeKeyID();
            $pageAttributeTypeHandle = $this->pageAttribute->getAttributeTypeHandle();

            if (array_key_exists($pageAttributeKeyID, $this->searchFilters)) {
                if (isset($this->searchFilters[$pageAttributeKeyID]) && !empty($this->searchFilters[$pageAttributeKeyID])) {
                    if (!is_array($this->searchFilters[$pageAttributeKeyID])) {
                        $this->searchFilters[$pageAttributeKeyID] = [$this->searchFilters[$pageAttributeKeyID]];
                    }
                    $clauseParts = [];
                    if (count($this->searchFilters[$pageAttributeKeyID]) > 0) {
                        $compare = $this->currentAttributeFilter['filterSelection'] == 'querystring_contains' ? ' LIKE ' : ' NOT LIKE ';
                        foreach ($this->searchFilters[$pageAttributeKeyID] as $this->pageAttributeElement) {
                            $this->pageAttributeElement = trim($this->pageAttributeElement);
                            if ($this->pageAttributeElement) {
                                $clauseParts[] = "(" . $this->currentAttributeFilter['handle'] . $compare . "'%/" . $this->escape($this->pageAttributeElement) . "%')";
                            }
                        }
                        if (count($clauseParts) > 0) {
                            $concat = $this->currentAttributeFilter['filterSelection'] == 'querystring_contains' ? ' AND ' : ' OR ';
                            $this->pageListPlus->filterByClause("(" . implode($concat, $clauseParts) . ")");
                        }
                    }
                }
            }
        }

    }

    private function getTreeNodePaths($currentValue)
    {
        $nodesPaths = [];
        if (is_array($currentValue)) {
            foreach ($currentValue as $node) {
                $nodesPaths[] = $node->getTreeNodeDisplayPath();
            }
        }
        return $nodesPaths;
    }
}