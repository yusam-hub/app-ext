<?php

namespace YusamHub\AppExt\SymfonyExt;

class AppKernel
{
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
    protected string $rootDir;
    protected string $appDir;
    protected string $configDir;
    protected string $storageDir;
    protected string $publicDir;
    protected string $envDir;
    protected string $databaseDir;

    public function __construct(array $params = [])
    {
        $this->rootDir = rtrim($params['rootDir']??'', DIRECTORY_SEPARATOR);
        $this->appDir = rtrim($params['appDir']??'', DIRECTORY_SEPARATOR);
        $this->configDir = rtrim($params['configDir']??'', DIRECTORY_SEPARATOR);
        $this->storageDir = rtrim($params['storageDir']??'', DIRECTORY_SEPARATOR);
        $this->publicDir = rtrim($params['publicDir']??'', DIRECTORY_SEPARATOR);
        $this->envDir = rtrim($params['envDir']??'', DIRECTORY_SEPARATOR);
        $this->databaseDir = rtrim($params['databaseDir']??'', DIRECTORY_SEPARATOR);
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
     * @param string $rootDir
     */
    public function setRootDir(string $rootDir): void
    {
        $this->rootDir = $rootDir;
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
     * @param string $appDir
     */
    public function setAppDir(string $appDir): void
    {
        $this->appDir = $appDir;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getConfigDir(string $path = ''): string
    {
        return $this->configDir . $path;
    }

    /**
     * @param string $configDir
     */
    public function setConfigDir(string $configDir): void
    {
        $this->configDir = $configDir;
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
     * @param string $storageDir
     */
    public function setStorageDir(string $storageDir): void
    {
        $this->storageDir = $storageDir;
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
     * @param string $publicDir
     */
    public function setPublicDir(string $publicDir): void
    {
        $this->publicDir = $publicDir;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getEnvDir(string $path = ''): string
    {
        return $this->envDir . $path;
    }

    /**
     * @param string $envDir
     */
    public function setEnvDir(string $envDir): void
    {
        $this->envDir = $envDir;
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
     * @param string $databaseDir
     */
    public function setDatabaseDir(string $databaseDir): void
    {
        $this->databaseDir = $databaseDir;
    }

}