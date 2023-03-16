<?php

namespace YusamHub\AppExt\JsTable;

use YusamHub\Helper\Numeric;
use YusamHub\JsonExt\JsonObject;

class JsTableQuery extends JsonObject
{
    const SORT_DIRECTION_ASC = 'asc';
    const SORT_DIRECTION_DESC = 'desc';
    const SORT_DIRECTIONS = [
        self::SORT_DIRECTION_ASC,
        self::SORT_DIRECTION_DESC,
    ];
    const PAGE_MAX = PHP_INT_MAX;
    const LIMIT_MAX = PHP_INT_MAX;

    public int $page = 1;
    public int $limit = 1;
    public string $sortFieldName = '';
    public string $sortDirection = self::SORT_DIRECTION_ASC;
    public array $filter = [];

    /**
     * @param null|array|string $source
     * @throws \ReflectionException
     */
    public function __construct($source = null)
    {
        $this->import($source);
    }

    /**
     * @param $source
     * @param array $filterKeys
     * @return void
     * @throws \ReflectionException
     */
    public function import($source, array $filterKeys = []): void
    {
        if (is_null($source)) return;

        $source = is_array($source) ? $source : (array) json_decode($source, true);

        if (!isset($source['page']) || is_array($source['page'])) $source['page'] = 1;
        if (!isset($source['limit']) || is_array($source['limit'])) $source['limit'] = 1;
        if (!isset($source['sortFieldName']) || is_array($source['sortFieldName'])) $source['sortFieldName'] = '';
        if (!isset($source['sortDirection']) || is_array($source['sortDirection'])) $source['sortDirection'] = self::SORT_DIRECTION_ASC;
        if (!isset($source['filter']) || !is_array($source['filter'])) $source['filter'] = [];

        $source['page'] = Numeric::clamp(intval($source['page']), 1, self::PAGE_MAX);
        $source['limit'] = Numeric::clamp(intval($source['limit']), 1, self::LIMIT_MAX);
        $source['sortFieldName'] = strval($source['sortFieldName']);
        $source['sortDirection'] = strval($source['sortDirection']);
        if (!in_array($source['sortDirection'], self::SORT_DIRECTIONS)) {
            $source['sortDirection'] = self::SORT_DIRECTION_ASC;
        }
        if (!is_array($source['filter'])) {
            unset($source['filter']);
        }

        parent::import($source, $filterKeys);
    }
}
