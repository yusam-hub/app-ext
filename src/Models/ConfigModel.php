<?php

namespace YusamHub\AppExt\Models;

class ConfigModel
{
    protected static string $dotKeyAsConfigItemDefault = '';
    protected static string $dotKeyAsConfigItems = '%s';

    /**
     * @param string|null $configItem
     * @return static
     */
    public static function newFromConfigItem(?string $configItem = null)
    {
        if (empty($configItem)) {
            $configItem =  app_ext_config(static::$dotKeyAsConfigItemDefault);
        }

        $config = app_ext_config(sprintf(static::$dotKeyAsConfigItems, $configItem));

        return new static($config);
    }

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        foreach($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}