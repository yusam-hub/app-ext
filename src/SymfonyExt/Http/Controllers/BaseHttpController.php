<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers;

use YusamHub\AppExt\Interfaces\GetSetLoggerInterface;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;

abstract class BaseHttpController implements GetSetLoggerInterface
{
    use GetSetLoggerTrait;
}