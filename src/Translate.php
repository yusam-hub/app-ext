<?php

namespace YusamHub\AppExt;

class Translate
{
    protected static ?Translate $instance = null;

    /**
     * @param Locale|null $locale
     * @return Translate
     */
    public static function instance(?Locale $locale = null): Translate
    {
        if (is_null(self::$instance)) {

            self::$instance = new static($locale);
        }

        return self::$instance ;
    }

    protected Locale $locale;
    protected array $localePaths;
    protected array $translateConfig = [];

    public function __construct(?Locale $locale = null)
    {
        if (is_null($locale)) {
            $this->locale = new Locale();
        } else {
            $this->locale = $locale;
        }
        $this->localePaths = app_ext_config('translates.localePaths');
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        return $this->locale;
    }

    /**
     * @param string|null $locale
     * @return Config
     */
    public function getTranslate(?string $locale = null): Config
    {
        if (empty($locale) || !in_array($locale, $this->locale->getLocales())) {
            $locale = $this->locale->getLocale();
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
        $data = $translate->get($dotKey,'');
        if (is_array($data)) {
            return $dotKey;
        }
        return strtr(strval($data), $replace);
    }
}