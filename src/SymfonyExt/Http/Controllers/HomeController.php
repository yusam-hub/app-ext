<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class HomeController extends BaseHttpController
{
    public static function routesRegister(RoutingConfigurator $routes): void
    {
        static::routesAdd($routes, ['OPTIONS', 'GET'], '/','actionHomeEmpty');
    }

    /**
     * @param Request $request
     * @return null
     */
    public function actionHomeEmpty(Request $request)
    {
        return null;
    }
}