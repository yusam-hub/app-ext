<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers;

use YusamHub\AppExt\Interfaces\GetSetLoggerInterface;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

abstract class BaseHttpController implements GetSetLoggerInterface
{
    use GetSetLoggerTrait;

    abstract public static function routesRegister(RoutingConfigurator $routes): void;

}