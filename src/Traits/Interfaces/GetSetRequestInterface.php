<?php

namespace YusamHub\AppExt\Traits\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface GetSetRequestInterface
{
    public function hasRequest(): bool;
    public function getRequest(): ?Request;
    public function setRequest(?Request $request): void;
}