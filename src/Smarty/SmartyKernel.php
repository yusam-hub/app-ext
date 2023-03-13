<?php

namespace YusamHub\AppExt\Smarty;

use YusamHub\AppExt\Redis\RedisKernel;
use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetLoggerInterface;
use YusamHub\RedisExt\RedisExt;
use YusamHub\SmartyExt\SmartyExt;

class SmartyKernel implements GetSetLoggerInterface, GetSetConsoleInterface
{
    use GetSetLoggerTrait;
    use GetSetConsoleTrait;

    protected static ?SmartyKernel $instance = null;
    protected array $templatesSchemes = [];

    /**
     * @return SmartyKernel
     */
    public static function global(): SmartyKernel
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
            self::$instance->setLogger(app_ext_logger());
        }
        return self::$instance;
    }

    /**
     * @param string|null $templateScheme
     * @return SmartyExt
     */
    public function smartyExt(?string $templateScheme = null): SmartyExt
    {
        if (is_null($templateScheme)) {
            $templateScheme = $this->getDefaultTemplateScheme();
        }

        if (isset($this->templatesSchemes[$templateScheme])) {
            return $this->templatesSchemes[$templateScheme];
        }

        $smartyExt = new SmartyExt((array) app_ext_config('smarty-ext.templates.' . $templateScheme, []));
        return $this->templatesSchemes[$templateScheme] = $smartyExt;
    }

    /**
     * @return string
     */
    public function getDefaultTemplateScheme(): string
    {
        return (string) app_ext_config('smarty-ext.default');
    }

    /**
     * @return array
     */
    public function getTemplateSchemes(): array
    {
        return array_keys((array) app_ext_config('smarty-ext.templates'));
    }
}