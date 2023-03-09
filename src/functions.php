<?php

use Psr\Log\LoggerInterface;
use YusamHub\AppExt\Config;
use YusamHub\AppExt\Db\DbKernel;
use YusamHub\AppExt\DotArray;
use YusamHub\AppExt\Env;
use YusamHub\AppExt\SymfonyExt\AppKernel;
use YusamHub\DbExt\PdoExt;

if (! function_exists('app')) {

    /**
     * @return AppKernel
     */
    function app(): AppKernel
    {
        return AppKernel::instance(app_ext_config('app'));
    }
}

if (! function_exists('db')) {

    /**
     * @return DbKernel
     */
    function db(): DbKernel
    {
        return DbKernel::instance(app_ext_config('database'));
    }
}

if (! function_exists('app_ext_logger')) {

    /**
     * @param string|null $channel
     * @param array $extra
     * @return LoggerInterface
     */
    function app_ext_logger(?string $channel = null, array $extra = []): LoggerInterface
    {
        return \YusamHub\AppExt\Logging::instance()->channel($channel, $extra);
    }
}

if (! function_exists('app_ext_dot_array')) {

    /**
     * @param DotArray|array|mixed $value
     * @return DotArray
     */
    function app_ext_dot_array($value): DotArray
    {
        return new DotArray($value);
    }
}

if (! function_exists('app_ext_env')) {

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    function app_ext_env(string $name, $default = null)
    {
        $globalValue = getenv($name);

        if (is_string($globalValue)) {
            return app_ext_evn_map_value($globalValue);
        }

        return Env::instance()->get($name, $default);
    }
}

if (! function_exists('app_ext_config')) {

    /**
     * @param string $dotKey
     * @param mixed $default
     * @return mixed
     */
    function app_ext_config(string $dotKey, $default = null)
    {
        return Config::instance()->get($dotKey, $default);
    }
}

if (! function_exists('app_ext_config_set')) {

    /**
     * @param string $dotKey
     * @param mixed $value
     * @return void
     */
    function app_ext_config_set(string $dotKey, $value): bool
    {
        return Config::instance()->set($dotKey, $value);
    }
}

if (! function_exists('app_ext_evn_map_value')) {

    /**
     * @param mixed $value
     * @return mixed
     */
    function app_ext_evn_map_value(string $value)
    {
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        if (preg_match('/\A([\'"])(.*)\1\z/', $value, $matches)) {
            return $matches[2];
        }
        if (preg_match('/^-?(\d{1,20})$/', $value)) {
            return intval($value);
        }
        if (preg_match('/^-?\d+(?:\.\d+)?$/', $value)) {
            return floatval($value);
        }
        return $value;
    }
}

if (! function_exists('app_ext_create_pdo')) {

    function app_ext_create_pdo(?string $connectionName = null): \PDO
    {
        if (is_null($connectionName)) {
            $connectionName = app_ext_config("database.default");
        }

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s',
            app_ext_config("database.connections.{$connectionName}.host") . ':' . app_ext_config("database.connections.{$connectionName}.port"),
            app_ext_config("database.connections.{$connectionName}.dbName")
        );

        return new \PDO(
            $dsn,
            app_ext_config("database.connections.{$connectionName}.user"),
            app_ext_config("database.connections.{$connectionName}.password"),
            [
                \PDO::ATTR_PERSISTENT => true
            ]
        );
    }
}

if (! function_exists('pdo_ext')) {

    function pdo_ext(?string $connectionName = null): PdoExt
    {
        return new PdoExt(app_ext_create_pdo($connectionName));
    }

}

if (! function_exists('app_ext_get_error_context')) {
    /**
     * @param Throwable $e
     * @param bool $includeTrace
     * @return array
     */
    function app_ext_get_error_context(\Throwable $e, bool $includeTrace = false): array
    {
        $out = [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile() . ":" . $e->getLine(),
            'class' => get_class($e),
        ];
        if ($e instanceof \YusamHub\AppExt\Exceptions\Interfaces\AppExtRuntimeExceptionInterface) {
            $out = array_merge($out, ['data' => $e->getData()]);
        }
        if ($includeTrace) {
            $out = array_merge($out, ['trace' => $e->getTrace()]);
        }
        return $out;
    }
}

if (! function_exists('app_ext_get_files')) {

    function app_ext_get_files(\Symfony\Component\HttpFoundation\Request $request): array
    {
        return (array) $request->attributes->get('_files');
    }
}