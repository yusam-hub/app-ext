<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class HomeController extends BaseHttpController
{
    public static function routesRegister(RoutingConfigurator $routes): void
    {
        $routes
            ->add(md5(__METHOD__), '/')
            ->controller(
                [static::class, 'actionHomeEmpty']
            )
            ->methods(['GET', 'HEAD'])
        ;
    }

    /**
     * @param Request $request
     * @return null
     */
    public function actionHomeEmpty(Request $request)
    {
        return null;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function actionHomeDebugDateTimeAsString(Request $request): string
    {
        return date("Y-m-d H:i:s");
    }

    /**
     * @param Request $request
     * @return array
     */
    public function actionHomeDebugDateTimeAsArray(Request $request): array
    {
        return [
            date("Y-m-d H:i:s")
        ];
    }
}