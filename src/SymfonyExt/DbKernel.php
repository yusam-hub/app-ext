<?php

namespace YusamHub\AppExt\SymfonyExt;
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
        if (isset($this->dbInstances[$name])) {
            return $this->dbInstances[$name];
        }
        if (isset($this->config['connections'][$name])) {
             return $this->dbInstances[$name] = new PdoExt(app_ext_create_pdo($name));
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
     * @return array
     */
    public function getConnectionNames(): array
    {
        return array_keys($this->config['connections']);
    }

}