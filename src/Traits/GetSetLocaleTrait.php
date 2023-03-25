<?php

namespace YusamHub\AppExt\Traits;

use YusamHub\AppExt\Locale;


trait GetSetLocaleTrait
{
    private ?Locale $locale = null;

    public function hasLocale(): bool
    {
        return !is_null($this->locale);
    }
    public function getLocale(): ?Locale
    {
        return $this->locale;
    }
    public function setLocale(?Locale $locale): void
    {
        $this->locale = $locale;
    }
}