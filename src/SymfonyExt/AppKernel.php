<?php

namespace YusamHub\AppExt\SymfonyExt;

use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetLoggerInterface;

class AppKernel
    implements
    GetSetLoggerInterface,
    GetSetConsoleInterface
{
    use GetSetConsoleTrait;
    use GetSetLoggerTrait;
    public static bool $RUN_IN_REACT_HTTP = false;

    protected static ?AppKernel $instance = null;

    /**
     * @param array $initParams
     * @return AppKernel
     */
    public static function instance(array $initParams = []): AppKernel
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($initParams);
        }

        return self::$instance ;
    }
    protected bool $isDebugging;
    protected string $rootDir;
    protected string $appDir;
    protected string $storageDir;
    protected string $publicDir;
    protected string $databaseDir;
    protected string $routesDir;

    public function __construct(array $params = [])
    {
        foreach($params as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return bool
     */
    public function isDebugging(): bool
    {
        return $this->isDebugging;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getRootDir(string $path = ''): string
    {
        return $this->rootDir . $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getAppDir(string $path = ''): string
    {
        return $this->appDir . $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getStorageDir(string $path = ''): string
    {
        return $this->storageDir . $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getPublicDir(string $path = ''): string
    {
        return $this->publicDir . $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getDatabaseDir(string $path = ''): string
    {
        return $this->databaseDir . $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getRoutesDir(string $path = ''): string
    {
        return $this->routesDir . $path;
    }

}