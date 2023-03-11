<?php

namespace YusamHub\AppExt\Db\Model;

use YusamHub\AppExt\Db\DbKernel;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ApiAuthorizeModelInterface;

/**
 * @method static ApiUserModel|null findModel(DbKernel $dbKernel, $pk)
 * @method static ApiUserModel findModelOrFail(DbKernel $dbKernel, $pk)
 * @method static ApiUserModel|null findModelByAttributes(DbKernel $dbKernel, array $attributes)
 * @method static ApiUserModel findModelByAttributesOrFail(DbKernel $dbKernel, array $attributes)
 */
class ApiUserModel extends DbModel implements ApiAuthorizeModelInterface
{
    protected ?string $connectionName = DB_APP_EXT_CONNECTION_DEFAULT;
    protected string $tableName = TABLE_APP_EXT_API_USERS;

    public int $id;
    public string $apiToken;
    public string $apiSign;
    public string $description;
    public ?string $blockedAt = null;
    public ?string $blockedDescription = null;
    public string $createdAt;
    public ?string $modifiedAt = null;

    protected function triggerBeforeInsert(): void
    {
        $this->createdAt = date(DATE_TIME_APP_EXT_FORMAT);
    }

    public function getAuthorizeIdentifierAsInt(): int
    {
        return $this->id;
    }

    public function getAuthorizeIdentifierAsString(): string
    {
        return strval($this->getAuthorizeIdentifierAsInt());
    }

}
