<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers;

use Symfony\Component\Routing\Loader\Configurator\RouteConfigurator;
use YusamHub\AppExt\Interfaces\GetSetLoggerInterface;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

abstract class BaseHttpController implements GetSetLoggerInterface
{
    use GetSetLoggerTrait;

    /**
     * @param RoutingConfigurator $routes
     * @return void
     */
    abstract public static function routesRegister(RoutingConfigurator $routes): void;

    /**
     * @param RoutingConfigurator $routes
     * @param array $methods
     * @param string $path
     * @param string $methodName
     * @param array $requirements
     * @return RouteConfigurator
     */
    protected static function routesAdd(RoutingConfigurator $routes, array $methods, string $path, string $methodName, array $requirements = []): RouteConfigurator
    {
        return $routes
            ->add(md5($path), $path)
            ->controller(
                [get_called_class(), $methodName]
            )
            ->methods($methods)
            ->requirements($requirements)
            ;
    }
}