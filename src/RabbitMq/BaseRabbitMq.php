<?php

namespace YusamHub\AppExt\RabbitMq;

use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetLoggerInterface;

abstract class BaseRabbitMq implements GetSetConsoleInterface, GetSetLoggerInterface
{
    use GetSetConsoleTrait;
    use GetSetLoggerTrait;

    protected string $connectionName;
    protected array $connectionConfig;
    public function __construct(?string $connectionName = null)
    {
        if (is_null($connectionName)) {
            $connectionName = app_ext_config("rabbit-mq.default");
        }
        $this->connectionName = $connectionName;
        $this->connectionConfig = app_ext_config("rabbit-mq.connections." . $connectionName);
    }

    /**
     * @return string
     */
    public function getConnectionName(): string
    {
        return $this->connectionName;
    }

    /**
     * @return array
     */
    public function getConnectionConfig(): array
    {
        return $this->connectionConfig;
    }


}