<?php

namespace YusamHub\AppExt\Db\Model\ApiUserModel;

use YusamHub\AppExt\Db\Model\PdoExtModel;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ApiAuthorizeModelInterface;

class ApiUserModel
    extends
    PdoExtModel
    implements
    ApiAuthorizeModelInterface,
    ApiUserModelInterface
{
    protected ?string $connectionName = DB_APP_EXT_CONNECTION_DEFAULT;
    protected string $tableName = TABLE_APP_EXT_API_USERS;

    protected function triggerBeforeSave(int $triggerType): void
    {
        if ($triggerType === self::TRIGGER_TYPE_SAVE_ON_INSERT) {
            $this->modelExtAttributeSet(self::ATTRIBUTE_NAME_CREATED_AT, date(DATE_TIME_APP_EXT_FORMAT));
        }
    }

    public function getAuthorizeIdentifierAsInt(): int
    {
        return $this->modelExtAttributeGet(self::ATTRIBUTE_NAME_ID);
    }

    public function getAuthorizeIdentifierAsString(): string
    {
        return strval($this->getAuthorizeIdentifierAsInt());
    }

}
