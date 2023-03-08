<?php

use Psr\Log\LoggerInterface;
use YusamHub\AppExt\Config;
use YusamHub\AppExt\DotArray;
use YusamHub\AppExt\Env;

if (! function_exists('app')) {

    /**
     * @return \YusamHub\AppExt\SymfonyExt\AppKernel
     */
    function app(): \YusamHub\AppExt\SymfonyExt\AppKernel
    {
        return \YusamHub\AppExt\SymfonyExt\AppKernel::instance(app_ext_config('app'));
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
