<?php

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use YusamHub\AppExt\Config;
use YusamHub\AppExt\Db\PdoExtKernel;
use YusamHub\AppExt\Env;
use YusamHub\AppExt\Exceptions\Interfaces\AppExtRuntimeExceptionInterface;
use YusamHub\AppExt\Redis\RedisKernel;
use YusamHub\AppExt\Smarty\SmartyKernel;
use YusamHub\AppExt\SymfonyExt\AppKernel;
use YusamHub\DbExt\Interfaces\PdoExtKernelInterface;
use YusamHub\DbExt\PdoExt;

if (! function_exists('app_ext')) {

    /**
     * @return AppKernel
     */
    function app_ext(): AppKernel
    {
        return AppKernel::instance(app_ext_config('app'));
    }
}

if (! function_exists('app_ext_db_global')) {

    /**
     * @return PdoExtKernelInterface
     */
    function app_ext_db_global(): PdoExtKernelInterface
    {
        return PdoExtKernel::global();
    }
}

if (! function_exists('app_ext_redis_global')) {

    /**
     * @return RedisKernel
     */
    function app_ext_redis_global(): RedisKernel
    {
        return RedisKernel::global();
    }
}

if (! function_exists('app_ext_smarty_global')) {

    /**
     * @return SmartyKernel
     */
    function app_ext_smarty_global(): SmartyKernel
    {
        return SmartyKernel::global();
    }
}

if (! function_exists('app_ext_pdo_ext')) {

    /**
     * @param string|null $connectionName
     * @param bool $newConnection
     * @return PdoExt
     */
    function app_ext_pdo_ext(?string $connectionName = null, bool $newConnection = false): PdoExt
    {
        if (is_null($connectionName)) {
            $connectionName = app_ext_config("database.default");
        }
        if (empty($connectionName)) {
            throw new \RuntimeException(sprintf("Unable to find database connection name [%s]", $connectionName));
        }
        return db_ext_mysql_pdo_ext_create_from_config(app_ext_config("database.connections.{$connectionName}"), $newConnection);
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

if (! function_exists('app_ext_locale')) {

    /**
     * @return \YusamHub\AppExt\Locale
     */
    function app_ext_locale(): \YusamHub\AppExt\Locale
    {
        return \YusamHub\AppExt\Locale::instance();
    }
}

if (! function_exists('app_ext_translate')) {

    /**
     * @param string $dotKey
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function app_ext_translate(string $dotKey, array $replace = [], ?string $locale = null): string
    {
        return \YusamHub\AppExt\Translate::instance()->translate($dotKey, $replace, $locale);
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
     * @return bool
     */
    function app_ext_config_set(string $dotKey, $value): bool
    {
        return Config::instance()->set($dotKey, $value);
    }
}

if (! function_exists('app_ext_config_has')) {

    /**
     * @param string $dotKey
     * @return bool
     */
    function app_ext_config_has(string $dotKey): bool
    {
        return Config::instance()->has($dotKey);
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
        if ($e instanceof AppExtRuntimeExceptionInterface) {
            $out = array_merge($out, ['data' => $e->getData()]);
        }
        if ($includeTrace) {
            $out = array_merge($out, ['trace' => $e->getTrace()]);
        }
        return $out;
    }
}

if (! function_exists('app_ext_get_files')) {

    /**
     * @param Request $request
     * @return array
     */
    function app_ext_get_files(Request $request): array
    {
        return (array) $request->attributes->get('_files');
    }
}