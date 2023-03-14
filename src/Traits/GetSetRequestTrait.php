<?php

namespace YusamHub\AppExt\Traits;

use Symfony\Component\HttpFoundation\Request;


trait GetSetRequestTrait
{
    private ?Request $request = null;

    public function hasRequest(): bool
    {
        return !is_null($this->request);
    }
    public function getRequest(): ?Request
    {
        return $this->request;
    }
    public function setRequest(?Request $request): void
    {
        $this->request = $request;
    }
}