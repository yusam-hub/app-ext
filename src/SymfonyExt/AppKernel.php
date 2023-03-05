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
    protected string $databaseMigrationDir;
    protected string $storageLogDir;

    public function __construct(array $params = [])
    {
        $this->rootDir = $params['rootDir']??'';
        $this->appDir = $params['appDir']??'';
        $this->configDir = $params['configDir']??'';
        $this->storageDir = $params['storageDir']??'';
        $this->publicDir = $params['publicDir']??'';
        $this->envDir = $params['envDir']??'';
        $this->databaseDir = $params['databaseDir']??'';
        $this->databaseMigrationDir = $params['databaseMigrationDir']??'';
        $this->storageLogDir = $params['storageLogDir']??'';
    }

    /**
     * @return string
     */
    public function getRootDir(): string
    {
        return $this->rootDir;
    }

    /**
     * @param string $rootDir
     */
    public function setRootDir(string $rootDir): void
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @return string
     */
    public function getAppDir(): string
    {
        return $this->appDir;
    }

    /**
     * @param string $appDir
     */
    public function setAppDir(string $appDir): void
    {
        $this->appDir = $appDir;
    }

    /**
     * @return string
     */
    public function getConfigDir(): string
    {
        return $this->configDir;
    }

    /**
     * @param string $configDir
     */
    public function setConfigDir(string $configDir): void
    {
        $this->configDir = $configDir;
    }

    /**
     * @return string
     */
    public function getStorageDir(): string
    {
        return $this->storageDir;
    }

    /**
     * @param string $storageDir
     */
    public function setStorageDir(string $storageDir): void
    {
        $this->storageDir = $storageDir;
    }

    /**
     * @return string
     */
    public function getPublicDir(): string
    {
        return $this->publicDir;
    }

    /**
     * @param string $publicDir
     */
    public function setPublicDir(string $publicDir): void
    {
        $this->publicDir = $publicDir;
    }

    /**
     * @return string
     */
    public function getEnvDir(): string
    {
        return $this->envDir;
    }

    /**
     * @param string $envDir
     */
    public function setEnvDir(string $envDir): void
    {
        $this->envDir = $envDir;
    }

    /**
     * @return string
     */
    public function getDatabaseDir(): string
    {
        return $this->databaseDir;
    }

    /**
     * @param string $databaseDir
     */
    public function setDatabaseDir(string $databaseDir): void
    {
        $this->databaseDir = $databaseDir;
    }

    /**
     * @return string
     */
    public function getDatabaseMigrationDir(): string
    {
        return $this->databaseMigrationDir;
    }

    /**
     * @param string $databaseMigrationDir
     */
    public function setDatabaseMigrationDir(string $databaseMigrationDir): void
    {
        $this->databaseMigrationDir = $databaseMigrationDir;
    }

    /**
     * @return string
     */
    public function getStorageLogDir(): string
    {
        return $this->storageLogDir;
    }

    /**
     * @param string $storageLogDir
     */
    public function setStorageLogDir(string $storageLogDir): void
    {
        $this->storageLogDir = $storageLogDir;
    }

}