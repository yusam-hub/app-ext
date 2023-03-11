<?php

namespace YusamHub\AppExt\Db;

use YusamHub\DbExt\PdoExt;

class DbKernel
{
    protected static ?DbKernel $instance = null;
    protected array $dbConnections = [];

    /**
     * @return DbKernel
     */
    public static function global(): DbKernel
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * @param string|null $connectionName
     * @return PdoExt|null
     */
    public function newPdoExt(?string $connectionName = null): ?PdoExt
    {
        if (is_null($connectionName)) {
            $connectionName = $this->getDefaultConnectionName();
        }

        if (isset($this->dbConnections[$connectionName])) {
            return $this->dbConnections[$connectionName];
        }

        $pdo_ext = app_ext_pdo_ext($connectionName, true);
        $pdo_ext->isDebugging = app()->hasLogger();
        $pdo_ext->onDebugLogCallback(function(string $message, array $context){
            app()->getLogger()->debug($message, $context);
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