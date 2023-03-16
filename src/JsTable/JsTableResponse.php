<?php

namespace YusamHub\AppExt\JsTable;

use YusamHub\JsonExt\JsonObject;

class JsTableResponse extends JsonObject
{
    public JsTableQuery $query;
    public JsTableDetail $detail;
    public JsTableData $data;

    public function __construct(string $rowClass)
    {
        $this->query = new JsTableQuery();
        $this->detail = new JsTableDetail();
        $this->data = new JsTableData($rowClass);
    }
}
