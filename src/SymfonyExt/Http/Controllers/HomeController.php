<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class HomeController extends BaseHttpController
{
    public static function routesRegister(RoutingConfigurator $routes): void
    {
        static::routesAdd($routes, ['GET', 'HEAD'], '/','actionHomeEmpty');
        static::routesAdd($routes, ['GET', 'POST', 'PUT', 'DELETE', 'HEAD'], '/debug/test', 'actionTest');
        static::routesAdd($routes, ['GET', 'HEAD'], '/debug/dt-as-string', 'actionHomeDebugDateTimeAsString');
        static::routesAdd($routes, ['GET', 'HEAD'], '/debug/dt-as-array', 'actionHomeDebugDateTimeAsArray');
        static::routesAdd($routes, ['GET', 'HEAD'], '/debug/env', 'actionHomeDebugEnvAsArray');
        static::routesAdd($routes, ['GET', 'HEAD'], '/debug/server', 'actionHomeDebugServerAsArray');
        static::routesAdd($routes, ['GET', 'HEAD'], '/debug/session', 'actionHomeDebugSessionAsArray');
        static::routesAdd($routes, ['GET', 'HEAD'], '/debug/cookie', 'actionHomeDebugCookieAsArray');
    }

    /**
     * @param Request $request
     * @return null
     */
    public function actionHomeEmpty(Request $request)
    {
        return null;
    }

    public function actionTest(Request $request): array
    {
        return [
            'dateTime' => date("Y-m-d H:i:s"),
            'method' => $request->getMethod(),
            'host' => $request->getHost(),
            'requestUri' => $request->getRequestUri(),
            'query' => $request->query->all(),
            'params' => $request->request->all(),
            'content' => $request->getContent(),
            'headers' => $request->headers->all(),
            'cookies' => $request->cookies->all(),
            'server' => $request->server->all(),
        ];
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

    public function actionHomeDebugEnvAsArray(Request $request): array
    {
        return (array) $_ENV;
    }

    public function actionHomeDebugServerAsArray(Request $request): array
    {
        return (array) $_SERVER;
    }

    public function actionHomeDebugSessionAsArray(Request $request): array
    {
        return (array) $_SESSION;
    }

    public function actionHomeDebugCookieAsArray(Request $request): array
    {
        return (array) $_COOKIE;
    }

}