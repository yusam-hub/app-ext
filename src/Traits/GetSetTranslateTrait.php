<?php

namespace YusamHub\AppExt\Traits;

use YusamHub\AppExt\Translate;


trait GetSetTranslateTrait
{
    private ?Translate $translate = null;

    public function hasTranslate(): bool
    {
        return !is_null($this->translate);
    }
    public function getTranslate(): ?Translate
    {
        return $this->translate;
    }
    public function setTranslate(?Translate $translate): void
    {
        $this->translate = $translate;
    }
}