<?php

namespace YusamHub\AppExt\Traits;

trait GetSetHttpControllerTrait
{
    use GetSetConsoleTrait;
    use GetSetLoggerTrait;
    use GetSetDbKernelTrait;
    use GetSetRedisKernelTrait;
    use GetSetCookieKernelTrait;
    use GetSetRequestTrait;

}