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

    /**
     * @var array|SmartyExt[]
     */
    protected array $templateSchemes = [];

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
     * @throws \SmartyException
     */
    public function template(?string $templateScheme = null): SmartyExt
    {
        if (is_null($templateScheme)) {
            $templateScheme = $this->getDefaultTemplateScheme();
        }

        if (isset($this->templateSchemes[$templateScheme])) {
            return $this->templateSchemes[$templateScheme];
        }

        $smartyExt = new SmartyExt((array) app_ext_config('smarty-ext.templates.' . $templateScheme, []));
        return $this->templateSchemes[$templateScheme] = $smartyExt;
    }

    /**
     * @return string
     */
    public function getDefaultTemplateScheme(): string
    {
        return (string) app_ext_config('smarty-ext.templateDefault');
    }

    /**
     * @return array
     */
    public function getTemplateSchemeNames(): array
    {
        return array_keys((array) app_ext_config('smarty-ext.templates'));
    }

    /**
     * @param string|null $templateScheme
     * @return void
     */
    public function templateClose(?string $templateScheme = null): void
    {
        if (is_null($templateScheme)) {
            $templateScheme = $this->getDefaultTemplateScheme();
        }

        if (isset($this->templateSchemes[$templateScheme])) {
            unset($this->templateSchemes[$templateScheme]);
        }
    }

    /**
     * @return array|SmartyExt[]
     */
    public function getTemplateSchemes(): array
    {
        return $this->templateSchemes;
    }
}