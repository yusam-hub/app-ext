<?php

namespace YusamHub\AppExt\Traits\Interfaces;

interface GetSetHttpControllerInterface
    extends
    GetSetRedisKernelInterface,
    GetSetDbKernelInterface,
    GetSetLoggerInterface,
    GetSetConsoleInterface,
    GetSetCookieKernelInterface,
    GetSetRequestInterface
{
}