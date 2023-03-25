<?php

namespace YusamHub\AppExt\Traits\Interfaces;

use YusamHub\AppExt\Locale;

interface GetSetLocaleInterface
{
    public function hasLocale(): bool;
    public function getLocale(): ?Locale;
    public function setLocale(?Locale $locale): void;
}