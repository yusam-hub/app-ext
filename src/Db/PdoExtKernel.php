<?php

namespace YusamHub\AppExt\Db;

use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetLoggerInterface;
use YusamHub\DbExt\Interfaces\PdoExtInterface;
use YusamHub\DbExt\Interfaces\PdoExtKernelInterface;

class PdoExtKernel extends \YusamHub\DbExt\PdoExtKernel implements GetSetLoggerInterface, GetSetConsoleInterface
{
    use GetSetLoggerTrait;
    use GetSetConsoleTrait;

    /**
     * @return PdoExtKernelInterface
     */
    public static function global(): PdoExtKernelInterface
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
            self::$instance->setLogger(app_ext_logger());
        }
        return self::$instance;
    }

    public function createPdoExt(string $connectionName): PdoExtInterface
    {
        $pdo_ext = app_ext_pdo_ext($connectionName, true);
        $pdo_ext->isDebugging = $this->hasLogger();
        $pdo_ext->onDebugLogCallback(function(string $message, array $context) use($connectionName) {
            $this->debug(sprintf('[DB:%s] %s', $connectionName, $message), $context);
        });
        return $pdo_ext;
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