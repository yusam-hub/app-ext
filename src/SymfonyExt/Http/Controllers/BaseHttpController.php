<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers;

use Symfony\Component\Routing\Loader\Configurator\RouteConfigurator;
use YusamHub\AppExt\Interfaces\GetSetLoggerInterface;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

abstract class BaseHttpController implements GetSetLoggerInterface
{
    use GetSetLoggerTrait;

    abstract public static function routesRegister(RoutingConfigurator $routes): void;

    protected static function routesAdd(RoutingConfigurator $routes, array $methods, string $path, string $methodName): RouteConfigurator
    {
        return $routes
            ->add(md5($path), $path)
            ->controller(
                [get_called_class(), $methodName]
            )
            ->methods($methods)
            ;
    }
}