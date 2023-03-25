<?php

namespace YusamHub\AppExt\Traits\Interfaces;

use YusamHub\AppExt\Translate;

interface GetSetTranslateInterface
{
    public function hasTranslate(): bool;
    public function getTranslate(): ?Translate;
    public function setTranslate(?Translate $translate): void;
}