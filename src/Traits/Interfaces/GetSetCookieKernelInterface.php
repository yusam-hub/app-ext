<?php

namespace YusamHub\AppExt\Traits\Interfaces;

use YusamHub\AppExt\SymfonyExt\CookieKernel;

interface GetSetCookieKernelInterface
{
    public function hasCookieKernel(): bool;
    public function getCookieKernel(): ?CookieKernel;
    public function setCookieKernel(?CookieKernel $cookieKernel): void;
}