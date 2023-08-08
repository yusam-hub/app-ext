<?php

namespace YusamHub\AppExt\Redis;

use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetLoggerInterface;
use YusamHub\RedisExt\RedisExt;
class RedisKernel implements GetSetLoggerInterface, GetSetConsoleInterface
{
    use GetSetLoggerTrait;
    use GetSetConsoleTrait;

    protected static ?RedisKernel $instance = null;

    /**
     * @var array|RedisExt[]
     */
    protected array $connections = [];

    /**
     * @return RedisKernel
     */
    public static function global(): RedisKernel
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
            self::$instance->setLogger(app_ext_logger());
        }
        return self::$instance;
    }

    /**
     * @param string|null $connectionName
     * @return RedisExt
     */
    public function connection(?string $connectionName = null): RedisExt
    {
        if (is_null($connectionName)) {
            $connectionName = $this->getDefaultConnectionName();
        }

        if (isset($this->connections[$connectionName])) {
            return $this->connections[$connectionName];
        }

        $redisExt = new RedisExt((array) app_ext_config('redis.connections.' . $connectionName, []));
        $redisExt->isDebugging = $this->hasLogger();
        $redisExt->onDebugLogCallback(function(string $message, array $context) use($connectionName) {
            $this->debug(sprintf('[REDIS:%s] %s', $connectionName, $message), $context);
        });
        return $this->connections[$connectionName] = $redisExt;
    }

    /**
     * @param string|null $connectionName
     * @return void
     * @throws \RedisException
     */
    public function connectionClose(?string $connectionName = null): void
    {
        if (is_null($connectionName)) {
            $connectionName = $this->getDefaultConnectionName();
        }

        if (isset($this->connections[$connectionName])) {
            $this->connections[$connectionName]->redis()->close();
            unset($this->connections[$connectionName]);
        }
    }

    /**
     * @return string
     */
    public function getDefaultConnectionName(): string
    {
        return (string) app_ext_config('redis.connectionDefault');
    }

    /**
     * @return array
     */
    public function getConnectionNames(): array
    {
        return array_keys((array) app_ext_config('redis.connections'));
    }

    /**
     * @return array|RedisExt[]
     */
    public function getConnections(): array
    {
        return $this->connections;
    }
}