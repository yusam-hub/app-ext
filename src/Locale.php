<?php

namespace YusamHub\AppExt;

class Locale
{
    protected static ?Locale $instance = null;

    /**
     * @return Locale
     */
    public static function instance(): Locale
    {
        if (is_null(self::$instance)) {

            self::$instance = new static();
        }

        return self::$instance ;
    }

    protected string $locale;
    public function __construct()
    {
        $this->locale = $this->getDefault();
    }

    public function getDefault(): string
    {
        return app_ext_config('locales.default');
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getLocales(): array
    {
        return app_ext_config('locales.locales');
    }

    public function setLocale(?string $locale = null): bool
    {
        if (in_array($locale, $this->getLocales())) {
            $this->locale = $locale;
            return true;
        }
        return false;
    }
}