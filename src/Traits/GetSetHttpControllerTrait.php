<?php

namespace YusamHub\AppExt\Traits;

use YusamHub\DbExt\Traits\GetSetPdoExtKernelTrait;

trait GetSetHttpControllerTrait
{
    use GetSetConsoleTrait;
    use GetSetLoggerTrait;
    use GetSetPdoExtKernelTrait;
    use GetSetRedisKernelTrait;
    use GetSetCookieKernelTrait;
    use GetSetRequestTrait;

}