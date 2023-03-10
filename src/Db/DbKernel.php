<?php

namespace YusamHub\AppExt\Db;

use YusamHub\DbExt\PdoExt;

class DbKernel
{
    protected static ?DbKernel $instance = null;

    protected array $config;
    protected array $dbInstances = [];

    /**
     * @param array $config
     * @return DbKernel
     */
    public static function instance(array $config = []): DbKernel
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($config);
        }

        return self::$instance ;
    }

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @param $name
     * @return PdoExt|null
     */
    public function __get($name): ?PdoExt
    {
       return $this->connection($name);
    }

    /**
     * @param string|null $name
     * @return PdoExt|null
     */
    public function connection(?string $name = null): ?PdoExt
    {
        if (empty($name)) {
            $name = $this->config['default'];
        }
        if (isset($this->dbInstances[$name])) {
            return $this->dbInstances[$name];
        }
        if (isset($this->config['connections'][$name])) {
            $this->dbInstances[$name] = pdo_ext($name);
            $this->dbInstances[$name]->isDebugging = true;
            $this->dbInstances[$name]->onDebugLogCallback(function(string $sql, array $bindings){
                app_ext_logger()->debug($sql, $bindings);
            });
            return $this->dbInstances[$name];
        }
        throw new \RuntimeException(sprintf("Unable to find database connection name [%s]", $name));
    }

    /**
     * @param $name
     * @return void
     */
    public function destroyConnection($name): void
    {
        if (isset($this->dbInstances[$name])) {
            unset($this->dbInstances[$name]);
        }
    }

    /**
     * @return void
     */
    public function destroyAllConnections(): void
    {
        foreach($this->getConnectionNames() as $name) {
            $this->destroyConnection($name);
        }
    }

    /**
     * @return string
     */
    public function getDefaultConnectionName(): string
    {
        return $this->config['default'];
    }

    /**
     * @return array
     */
    public function getConnectionNames(): array
    {
        return array_keys($this->config['connections']);
    }

}