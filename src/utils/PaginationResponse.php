<?php

namespace Rev\Utils;

use Phalcon\Paginator\Repository as Paginator;

/**
 * Class PaginationResponse
 * @package Rev\Utils
 */
final class PaginationResponse
{
    /**
     * @param string $prefix
     * @param Paginator $page
     * @param int $limit
     * @param array $acceptedParams
     * @param array $data
     * @return array
     */
    public static function getResponse(
        string $prefix,
        Paginator $page,
        int $limit,
        array $acceptedParams,
        array $data
    ): array
    {
        $params = self::paramStr($acceptedParams);

        $last = ($page->last) ?: 1; // if last is 0, use 1 instead

        return [
            'links' => [
                'current' => $prefix . '?page=' . $page->current . '&limit=' . $limit . $params,
                'first' => $prefix . '?page=' . $page->first . '&limit=' . $limit . $params,
                'last' => $prefix . '?page=' . $last . '&limit=' . $limit . $params,
                'prev' => ($page->previous != $page->current) ? $prefix . '?page=' . $page->previous . '&limit=' . $limit . $params : null,
                'next' => ($page->next != $page->current) ? $prefix . '?page=' . $page->next . '&limit=' . $limit . $params : null,
            ],
            "count" => $page->total_items,
            'data' => $data,
        ];
    }

    /**
     * Create ending uri from array parameters
     * @param array $acceptedParams
     * @return string
     */
    private static function paramStr(array $acceptedParams): string
    {
        $params = [];
        foreach ($acceptedParams as $key => $var) {
            if ($var) {
                $params[] = $key . '=' . $var;
            }
        }

        return ($params) ? '&' . implode('&', $params) : '';
    }
}