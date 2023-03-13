<?php

namespace YusamHub\AppExt\Traits;

use YusamHub\AppExt\SymfonyExt\CookieKernel;

trait GetSetCookieKernelTrait
{
    private ?CookieKernel $cookieKernel = null;

    public function hasCookieKernel(): bool
    {
        return !is_null($this->cookieKernel);
    }
    public function getCookieKernel(): ?CookieKernel
    {
        return $this->cookieKernel;
    }
    public function setCookieKernel(?CookieKernel $cookieKernel): void
    {
        $this->cookieKernel = $cookieKernel;
    }
}