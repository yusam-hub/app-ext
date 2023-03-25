<?php

namespace YusamHub\AppExt;

use Dotenv\Repository\RepositoryInterface;

class Env
{
    protected static ?Env $instance = null;

    /**
     * @param string|null $envDir
     * @param string|null $envFile
     * @return Env
     */
    public static function instance(?string $envDir = null, ?string $envFile = '.env'): Env
    {
        if (is_null(self::$instance)) {

            self::$instance = new static($envDir, $envFile);
        }

        return self::$instance ;
    }

    /**
     * @var RepositoryInterface|null
     */
    protected ?RepositoryInterface $repository = null;

    protected ?string $envFile = null;
    protected ?string $envDir = null;

    /**
     * @param string|null $envDir
     * @param string|null $envFile
     */
    public function __construct(?string $envDir = null, ?string $envFile = '.env')
    {
        if (!is_null($envDir)) {
            $this->envDir = $envDir;
        }
        if (!is_null($envFile)) {
            $this->envFile = $envFile;
        }
    }

    /**
     * @return void
     */
    protected function init(): void
    {
        if (is_null($this->repository)) {
            $this->repository = \Dotenv\Repository\RepositoryBuilder::createWithDefaultAdapters()
                ->immutable()
                ->make();

            \Dotenv\Dotenv::create($this->repository, realpath(rtrim($this->envDir, DIRECTORY_SEPARATOR)), $this->envFile)->safeLoad();
        }
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $this->init();
        return
            \PhpOption\Option::fromValue($this->repository->get($key))
            ->map(
                function ($value)
                {
                    return app_ext_evn_map_value($value);
                }
            )
            ->getOrCall(function () use ($default) {
                return $default instanceof \Closure ? $default() : $default;
            });
    }
}