<?php

namespace YusamHub\AppExt;

class Translate
{
    protected static ?Translate $instance = null;

    /**
     * @return Translate
     */
    public static function instance(): Translate
    {
        if (is_null(self::$instance)) {

            self::$instance = new static();
        }

        return self::$instance ;
    }

    protected array $localePaths;
    protected array $translateConfig = [];

    public function __construct()
    {
        $this->localePaths = app_ext_config('translates.localePaths');
    }

    /**
     * @param string|null $locale
     * @return Config
     */
    public function getTranslate(?string $locale = null): Config
    {
        if (empty($locale) || !in_array($locale, app_ext_locale()->getLocales())) {
            $locale = app_ext_locale()->getLocale();
        }

        $key = md5($locale . $this->localePaths[$locale]);

        if (isset($this->translateConfig[$key])) {
            return $this->translateConfig[$key];
        }

        return $this->translateConfig[$key] = new Config($this->localePaths[$locale]);
    }

    /**
     * @param string $dotKey
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    public function translate(string $dotKey, array $replace = [], ?string $locale = null): string
    {
        $translate = $this->getTranslate($locale);
        return strtr((string) $translate->get($dotKey,''), $replace);
    }
}