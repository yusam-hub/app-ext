<?php

namespace YusamHub\AppExt\Db\Model;

/**
 * @method static ApiUserModel|null findModel($pk)
 * @method static ApiUserModel findModelOrFail($pk)
 * @method static ApiUserModel|null findModelByAttributes(array $attributes)
 * @method static ApiUserModel findModelByAttributesOrFail(array $attributes)
 */
class ApiUserModel extends DbModel
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
}
