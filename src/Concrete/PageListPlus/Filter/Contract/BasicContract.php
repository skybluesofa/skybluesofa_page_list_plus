<?php 
namespace Concrete\Package\SkybluesofaPageListPlus\PageListPlus\Filter\Contract;

defined('C5_EXECUTE') or die("Access Denied.");

class BasicContract extends FilterContract
{
    protected $pageAttributeKeyId = null;

    public function run()
    {
        if (in_array($this->currentAttributeFilter['filterSelection'], ['past', 'future', 'not_future', 'today_and_future', 'yesterday', 'today', 'tomorrow', 'last_year', 'this_year', 'next_year', 'last_365', 'next_365'])) {
            $this->runDateFilter();
        } else {
            $this->pageAttributeKeyId = $this->pageAttribute->getAttributeKeyID();
            $this->pageAttributeTypeHandle = $this->pageAttribute->getAttributeTypeHandle();
            if (!is_array($this->searchFilters[$this->pageAttributeKeyId])) {
                $this->searchFilters[$this->pageAttributeKeyId] = [$this->searchFilters[$this->pageAttributeKeyId]];
            }
            if (strpos($this->currentAttributeFilter['filterSelection'], 'attribute') !== false) {
                if ($this->currentAttributeFilter['filterSelection'] == 'equals_attribute') {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "=ak_" . $this->currentAttributeFilter['val1'] . ")");
                } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_equals_attribute') {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "!=ak_" . $this->currentAttributeFilter['val1'] . ")");
                } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than_attribute') {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "<ak_" . $this->currentAttributeFilter['val1'] . ")");
                } elseif ($this->currentAttributeFilter['filterSelection'] == 'less_than_or_equals_attribute') {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "<=ak_" . $this->currentAttributeFilter['val1'] . ")");
                } elseif ($this->currentAttributeFilter['filterSelection'] == 'greater_than_attribute') {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">ak_" . $this->currentAttributeFilter['val1'] . ")");
                } elseif ($this->currentAttributeFilter['filterSelection'] == 'greater_than_or_equals_attribute') {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">=ak_" . $this->currentAttributeFilter['val1'] . ")");
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_empty') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_empty') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'equals') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "='" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . " LIKE '%\\n" . $this->currentAttributeFilter['val1'] . "\\n%')");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_equals') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "!='" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . " NOT LIKE '%\\n" . $this->currentAttributeFilter['val1'] . "\\n%')");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'in_list') {
                if ($this->currentAttributeFilter['val1']) {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['val1'] . "')");
                } else {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'in_list_all') {
                if ($this->currentAttributeFilter['val1']) {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['val1'] . "')");
                } else {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_in_list') {
                if ($this->currentAttributeFilter['val1']) {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['val1'] . "')");
                } else {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'contains' && $this->currentAttributeFilter['val1']) {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '%" . $this->currentAttributeFilter['val1'] . "%')");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_contains' && $this->currentAttributeFilter['val1']) {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " NOT LIKE '%" . $this->currentAttributeFilter['val1'] . "%' OR " . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_exactly') {
                if ($this->currentAttributeFilter['val1']) {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['val1'] . "')");
                } else {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'is_not_exactly') {
                if ($this->currentAttributeFilter['val1']) {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " NOT LIKE '" . $this->currentAttributeFilter['val1'] . "')");
                } else {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'matches_all' || $this->currentAttributeFilter['filterSelection'] == 'matches_any') {
                if ($this->pageAttributeKeyHandle == "select") {
                    $clause = [];
                    if (is_object($this->currentAttributeFilter['currentValue'])) {
                        foreach ($this->currentAttributeFilter['currentValue']->getOptions() as $val) {
                            $clause[] = "(" . $this->currentAttributeFilter['handle'] . " LIKE '%\\n" . $this->escape($val->value) . "\\n%')";
                        }
                        if (count($clause)) {
                            if ($this->currentAttributeFilter['filterSelection'] == 'matches_all') {
                                $this->pageListPlus->filterByClause("(" . implode(' AND ', $clause) . ")");
                            } else {
                                $this->pageListPlus->filterByClause("((" . implode(' OR ', $clause) . ") AND (" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL))");
                            }
                        }
                    } else {
                        $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
                    }
                } else {
                    if ($this->currentAttributeFilter['currentValue']) {
                        $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['currentValue'] . "')");
                    } else {
                        $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "='' OR " . $this->currentAttributeFilter['handle'] . " IS NULL)");
                    }
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'querystring_all' || $this->currentAttributeFilter['filterSelection'] == 'querystring_any') {
                if ($this->hasSearchValue()) {
                    if (!is_array($this->searchFilters[$this->pageAttributeKeyId])) {
                        $this->searchFilters[$this->pageAttributeKeyId] = [$this->searchFilters[$this->pageAttributeKeyId]];
                    }
                    $startWildcard = ($this->currentAttributeFilter['isDate'] && $this->currentAttributeFilter['dateDisplayMode'] == 'date') ? '' : '%';
                    $clauseParts = [];
                    if (count($this->searchFilters[$this->pageAttributeKeyId]) > 0) {
                        foreach ($this->searchFilters[$this->pageAttributeKeyId] as $this->pageAttributeElement) {
                            $this->pageAttributeElement = trim($this->pageAttributeElement);
                            $clausePart = false;
                            if ($this->pageAttributeElement) {
                                if ($this->pageAttributeKeyHandle == "select") {
                                    $clausePart = "(" . $this->currentAttributeFilter['handle'] . " LIKE '%\\n" . $this->escape($this->pageAttributeElement) . "\\n%')";
                                } else {
                                    $clausePart = "(";
                                    $clausePart .= $this->currentAttributeFilter['handle'] . " LIKE '" . $startWildcard . $this->escape($this->pageAttributeElement) . "%'";
                                    $clausePart .= ")";
                                }
                            }
                            if ($clausePart) {
                                $clauseParts[] = $clausePart;
                            }
                        }
                        if (count($clauseParts) > 0) {
                            $concat = $this->currentAttributeFilter['filterSelection'] == 'querystring_all' ? ' AND ' : ' OR ';
                            $this->pageListPlus->filterByClause("(" . implode($concat, $clauseParts) . ")");
                        }

                    }
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_matches_all') {
                if ($this->pageAttributeKeyHandle == "select") {
                    $clause = [];
                    if (is_object($this->currentAttributeFilter['currentValue'])) {
                        foreach ($this->currentAttributeFilter['currentValue']->getOptions() as $val) {
                            $clause[] = "(" . $this->currentAttributeFilter['handle'] . " NOT LIKE '%\\n" . $val->value . "\\n%')";
                        }
                        if (count($clause)) {
                            $clauses = "(";
                            $clauses .= "(" . implode(' AND ', $clause) . ")";
                            $clauses .= " OR (" . $this->currentAttributeFilter['handle'] . "='')";
                            $clauses .= " OR (" . $this->currentAttributeFilter['handle'] . " IS NULL)";
                            $clauses .= ")";
                            $this->pageListPlus->filterByClause($clauses);
                        }
                    } else {
                        $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
                    }
                } else {
                    if ($this->currentAttributeFilter['currentValue']) {
                        $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " NOT LIKE '" . $this->currentAttributeFilter['currentValue'] . "')");
                    } else {
                        $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "!='' AND " . $this->currentAttributeFilter['handle'] . " IS NOT NULL)");
                    }
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_querystring_all') {
                if ($this->hasSearchValue()) {
                    if ($this->pageAttributeKeyHandle == "select") {
                        $clause = [];
                        foreach ($this->searchFilters[$this->pageAttributeKeyId] as $this->pageAttributeElement) {
                            $clause[] = "(" . $this->currentAttributeFilter['handle'] . " NOT LIKE '%\\n" . $this->escape($this->pageAttributeElement) . "\\n%')";
                        }
                        if (count($clause)) {
                            $clauses = "(";
                            $clauses .= "(" . implode(' AND ', $clause) . ")";
                            $clauses .= " OR (" . $this->currentAttributeFilter['handle'] . "='')";
                            $clauses .= " OR (" . $this->currentAttributeFilter['handle'] . " IS NULL)";
                            $clauses .= ")";
                            $this->pageListPlus->filterByClause($clauses);
                        }
                    } else {
                        $this->pageAttributeElement = $this->escape($this->searchFilters[$this->pageAttributeKeyId][0]);
                        $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " NOT LIKE '%" . $this->pageAttributeElement . "%')");
                    }
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'starts_with') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '" . $this->currentAttributeFilter['val1'] . "%')");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'ends_with') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . " LIKE '%" . $this->currentAttributeFilter['val1'] . "')");
            } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['less_than', 'less_than_allow_negatives'])) {
                $filterClause = $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val1'] . "'";
                if (strpos($this->currentAttributeFilter['filterSelection'], 'allow_negatives') === false && in_array($this->pageAttributeKeyHandle, ['number'])) {
                    $filterClause .= " && " . $this->currentAttributeFilter['handle'] . ">'0'";
                }
                $this->pageListPlus->filterByClause($filterClause);
            } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['less_than_match', 'less_than_match_allow_negatives'])) {
                if (in_array($this->pageAttributeKeyHandle, ['number'])) {
                    $currentValue = $this->currentAttributeFilter['currentValue'];
                    if (empty($currentValue)) {
                        $currentValue = 0;
                    }
                    $filterClause = $this->currentAttributeFilter['handle'] . "<'" . $currentValue . "'";
                    if (strpos($this->currentAttributeFilter['filterSelection'], 'allow_negatives') === false) {
                        $filterClause .= " && " . $this->currentAttributeFilter['handle'] . ">'0'";
                    }
                } else {
                    $filterClause = $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['currentValue'] . "'";
                }
                $this->pageListPlus->filterByClause($filterClause);
            } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['less_than_querystring', 'less_than_querystring_allow_negatives'])) {
                if ($this->currentAttributeFilter['isStandardProperty'])
                    $filterClause = $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['currentValue'] . "'";
                elseif (array_key_exists($this->pageAttributeKeyId, $this->searchFilters) && isset($this->searchFilters[$this->pageAttributeKeyId][0]) && !empty($this->searchFilters[$this->pageAttributeKeyId][0])) {
                    $filterClause = $this->currentAttributeFilter['handle'] . "<'" . $this->searchFilters[$this->pageAttributeKeyId][0] . "'";
                }
                if (strpos($this->currentAttributeFilter['filterSelection'], 'allow_negatives') === false && in_array($this->pageAttributeKeyHandle, ['number'])) {
                    $filterClause .= " && " . $this->currentAttributeFilter['handle'] . ">'0'";
                }
                $this->pageListPlus->filterByClause($filterClause);
            } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['less_than_or_equal_to', 'less_than_or_equal_to_allow_negatives'])) {
                $filterClause = $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val1'] . "'";
                if (strpos($this->currentAttributeFilter['filterSelection'], 'allow_negatives') === false && in_array($this->pageAttributeKeyHandle, ['number'])) {
                    $filterClause .= " && " . $this->currentAttributeFilter['handle'] . ">'0'";
                }
                $this->pageListPlus->filterByClause($filterClause);
            } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['less_than_or_equal_to_match', 'less_than_or_equal_to_match_allow_negatives'])) {
                $filterClause = $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['currentValue'] . "'";
                if (strpos($this->currentAttributeFilter['filterSelection'], 'allow_negatives') === false && in_array($this->pageAttributeKeyHandle, ['number'])) {
                    $filterClause .= " && " . $this->currentAttributeFilter['handle'] . ">'0'";
                }
                $this->pageListPlus->filterByClause($filterClause);
            } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['less_than_or_equal_to_querystring', 'less_than_or_equal_to_querystring_allow_negatives'])) {
                if ($this->currentAttributeFilter['isStandardProperty'])
                    $filterClause = $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['currentValue'] . "'";
                elseif (array_key_exists($this->pageAttributeKeyId, $this->searchFilters) && isset($this->searchFilters[$this->pageAttributeKeyId][0]) && !empty($this->searchFilters[$this->pageAttributeKeyId][0])) {
                    $filterClause = $this->currentAttributeFilter['handle'] . "<='" . $this->searchFilters[$this->pageAttributeKeyId][0] . "'";
                }
                if (strpos($this->currentAttributeFilter['filterSelection'], 'allow_negatives') === false && in_array($this->pageAttributeKeyHandle, ['number'])) {
                    $filterClause .= " && " . $this->currentAttributeFilter['handle'] . ">'0'";
                }
                $this->pageListPlus->filterByClause($filterClause);
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val1'] . "')");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_match') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['currentValue'] . "')");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_querystring') {
                if ($this->currentAttributeFilter['isStandardProperty'])
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['currentValue'] . "')");
                elseif (array_key_exists($this->pageAttributeKeyId, $this->searchFilters) && isset($this->searchFilters[$this->pageAttributeKeyId][0]) && !empty($this->searchFilters[$this->pageAttributeKeyId][0])) {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">'" . $this->searchFilters[$this->pageAttributeKeyId][0] . "')");
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_or_equal_to') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val1'] . "')");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_or_equal_to_match') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['currentValue'] . "')");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'more_than_or_equal_to_querystring') {
                if ($this->currentAttributeFilter['isStandardProperty'])
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['currentValue'] . "')");
                elseif (array_key_exists($this->pageAttributeKeyId, $this->searchFilters) && isset($this->searchFilters[$this->pageAttributeKeyId][0]) && !empty($this->searchFilters[$this->pageAttributeKeyId][0])) {
                    $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">='" . $this->searchFilters[$this->pageAttributeKeyId][0] . "')");
                }
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'between_inclusive') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val2'] . "')");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'between_exclusive') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val1'] . "' AND " . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val2'] . "')");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_between_inclusive') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "<='" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . ">='" . $this->currentAttributeFilter['val2'] . "')");
            } elseif ($this->currentAttributeFilter['filterSelection'] == 'not_between_exclusive') {
                $this->pageListPlus->filterByClause("(" . $this->currentAttributeFilter['handle'] . "<'" . $this->currentAttributeFilter['val1'] . "' OR " . $this->currentAttributeFilter['handle'] . ">'" . $this->currentAttributeFilter['val2'] . "')");
            } elseif (in_array($this->currentAttributeFilter['filterSelection'], ['between_inclusive_querystring', 'between_exclusive_querystring', 'not_between_inclusive_querystring', 'not_between_exclusive_querystring'])) {
                if ($this->hasSearchValue()) {
                    if (!is_array($this->searchFilters[$this->pageAttributeKeyId])) {
                        $this->searchFilters[$this->pageAttributeKeyId] = [$this->searchFilters[$this->pageAttributeKeyId]];
                    }
                    if (count($this->searchFilters[$this->pageAttributeKeyId]) > 0) {
                        if (count($this->searchFilters[$this->pageAttributeKeyId]) == 1) {
                            $this->searchFilters[$this->pageAttributeKeyId][] = 0;
                        }
                        $searchValues[0] = min($this->searchFilters[$this->pageAttributeKeyId]);
                        $searchValues[1] = max($this->searchFilters[$this->pageAttributeKeyId]);

                        $searchComparisons = [
                            'between_inclusive_querystring' => [">=","<="],
                            'between_exclusive_querystring' => [">","<"],
                            'not_between_inclusive_querystring' => ["<=",">="],
                            'not_ between_exclusive_querystring' => ["<",">"],
                        ];
                        $this->pageListPlus->filterByClause(
                            $this->currentAttributeFilter['handle'] .
                            $searchComparisons[$this->currentAttributeFilter['filterSelection']][0]."'".$searchValues[0]."'" .
                            " AND " .
                            $this->currentAttributeFilter['handle'] .
                            $searchComparisons[$this->currentAttributeFilter['filterSelection']][1]."'".$searchValues[1]."'"
                        );
                    }
                }
            }
        }
    }

    private function hasSearchValue()
    {
        if (array_key_exists($this->pageAttributeKeyId, $this->searchFilters)) {
            if (isset($this->searchFilters[$this->pageAttributeKeyId]) && !empty($this->searchFilters[$this->pageAttributeKeyId])) {
                return true;
            }
        }
        return false;
    }
}

