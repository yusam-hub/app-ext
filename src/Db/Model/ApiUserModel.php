<?php

namespace YusamHub\AppExt\Db\Model;

use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ApiAuthorizeModelInterface;
use YusamHub\DbExt\Interfaces\PdoExtKernelInterface;

/**
 * @method static ApiUserModel|null findModel(PdoExtKernelInterface $pdoExtKernel, $pk)
 * @method static ApiUserModel findModelOrFail(PdoExtKernelInterface $pdoExtKernel, $pk)
 * @method static ApiUserModel|null findModelByAttributes(PdoExtKernelInterface $pdoExtKernel, array $attributes)
 * @method static ApiUserModel findModelByAttributesOrFail(PdoExtKernelInterface $pdoExtKernel, array $attributes)
 */
class ApiUserModel extends PdoExtModel implements ApiAuthorizeModelInterface
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

    protected function triggerBeforeSave(int $triggerType): void
    {
        if ($triggerType === self::TRIGGER_TYPE_SAVE_ON_INSERT) {
            $this->createdAt = date(DATE_TIME_APP_EXT_FORMAT);
        }
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
