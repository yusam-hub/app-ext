<?php

namespace YusamHub\AppExt\RabbitMq;

use Bunny\Async\Client;
use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use YusamHub\AppExt\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Interfaces\GetSetLoggerInterface;
use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;

abstract class BaseRabbitMq implements GetSetConsoleInterface, GetSetLoggerInterface
{
    use GetSetConsoleTrait;
    use GetSetLoggerTrait;
    protected LoopInterface $reactLoop;
    protected Client $asyncClient;
    protected string $connectionName;
    protected array $connectionConfig;
    public function __construct(?string $connectionName = null)
    {
        if (is_null($connectionName)) {
            $connectionName = app_ext_config("rabbit-mq.default");
        }
        $this->connectionName = $connectionName;
        $this->connectionConfig = app_ext_config("rabbit-mq.connections." . $connectionName);
        $this->reactLoop = Loop::get();
        $this->asyncClient = new Client($this->reactLoop, $this->connectionConfig);
    }

    /**
     * @return LoopInterface
     */
    public function getReactLoop(): LoopInterface
    {
        return $this->reactLoop;
    }

    /**
     * @return Client
     */
    public function getAsyncClient(): Client
    {
        return $this->asyncClient;
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