<?php

namespace Rev\Utils;

use Phalcon\Mvc\Model\Query\Builder;

/**
 * Class PaginationSort
 * @package Rev\Utils
 */
final class PaginationSort
{
    /**
     * @param Builder $query
     * @param string $sort
     * @param string $idRef
     *
     * @return Builder
     */
    public static function sort(Builder $query, string $sort, string $idRef): Builder
    {
        $sortBys = explode(',', $sort);

        foreach ($sortBys as $sortBy) {
            $sortBy = explode(':', $sortBy);

            if ($sortBy[0] == 'id') {
                $sortBy[0] = $idRef;
            }

            $query = $query->orderBy($sortBy[0] . ' ' . $sortBy[1] . ', ' . $idRef . ' ' . $sortBy[1]);
        }

        return $query;
    }
}
