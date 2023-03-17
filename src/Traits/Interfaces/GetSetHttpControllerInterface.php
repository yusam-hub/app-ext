<?php

namespace YusamHub\AppExt\Traits\Interfaces;

use YusamHub\DbExt\Interfaces\GetSetPdoExtKernelInterface;

interface GetSetHttpControllerInterface
    extends
    GetSetRedisKernelInterface,
    GetSetPdoExtKernelInterface,
    GetSetLoggerInterface,
    GetSetConsoleInterface,
    GetSetCookieKernelInterface,
    GetSetRequestInterface
{
}