<?php

namespace YusamHub\AppExt\Db;

use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetLoggerInterface;
use YusamHub\DbExt\PdoExt;

class DbKernel implements GetSetLoggerInterface, GetSetConsoleInterface
{
    use GetSetLoggerTrait;
    use GetSetConsoleTrait;

    protected static ?DbKernel $instance = null;
    protected array $dbConnections = [];

    /**
     * @return DbKernel
     */
    public static function global(): DbKernel
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
            self::$instance->setLogger(app_ext_logger());
        }
        return self::$instance;
    }

    /**
     * @param string|null $connectionName
     * @return PdoExt
     */
    public function pdoExt(?string $connectionName = null): PdoExt
    {
        if (is_null($connectionName)) {
            $connectionName = $this->getDefaultConnectionName();
        }

        if (isset($this->dbConnections[$connectionName])) {
            return $this->dbConnections[$connectionName];
        }

        $pdo_ext = app_ext_pdo_ext($connectionName, true);
        $pdo_ext->isDebugging = $this->hasLogger();
        $pdo_ext->onDebugLogCallback(function(string $message, array $context) use($connectionName) {
            $this->debug(sprintf('[DB:%s] %s', $connectionName, $message), $context);
        });
        return $this->dbConnections[$connectionName] = $pdo_ext;
    }

    /**
     * @return string
     */
    public function getDefaultConnectionName(): string
    {
        return (string) app_ext_config('database.default');
    }

    /**
     * @return array
     */
    public function getConnectionNames(): array
    {
        return array_keys((array) app_ext_config('database.connections'));
    }

}