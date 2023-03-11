<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers;

use Symfony\Component\Routing\Loader\Configurator\RouteConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use YusamHub\AppExt\Db\DbKernel;
use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetDbKernelTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetDbKernelInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetHttpControllerInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetLoggerInterface;

abstract class BaseHttpController
    implements
    GetSetHttpControllerInterface,
    GetSetConsoleInterface,
    GetSetLoggerInterface,
    GetSetDbKernelInterface
{
    use GetSetConsoleTrait;
    use GetSetLoggerTrait;
    use GetSetDbKernelTrait;

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

    public function __construct()
    {
        $this->setDbKernel(new DbKernel());
    }
}