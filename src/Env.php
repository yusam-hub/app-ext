<?php

namespace YusamHub\AppExt;

use Dotenv\Repository\RepositoryInterface;

class Env
{
    public static string $ENV_DIR = '';
    public static string $ENV_FILE = '.env';
    protected static ?Env $instance = null;

    /**
     * @return Env
     */
    public static function instance(): Env
    {
        if (is_null(self::$instance)) {

            self::$instance = new static();
        }

        return self::$instance ;
    }

    /**
     * @var RepositoryInterface|null
     */
    protected ?RepositoryInterface $repository = null;

    /**
     * @return void
     */
    protected function init(): void
    {
        if (is_null($this->repository)) {
            $this->repository = \Dotenv\Repository\RepositoryBuilder::createWithDefaultAdapters()
                ->immutable()
                ->make();

            \Dotenv\Dotenv::create($this->repository, rtrim($this::$ENV_DIR, DIRECTORY_SEPARATOR), $this::$ENV_FILE)->safeLoad();
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