<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ControllerMiddlewareInterface;
use YusamHub\AppExt\SymfonyExt\Http\Traits\ApiAuthorizeTrait;
use YusamHub\AppExt\SymfonyExt\Http\Traits\ControllerMiddlewareTrait;

class HomeController extends BaseHttpController implements ControllerMiddlewareInterface
{
    use ControllerMiddlewareTrait;
    use ApiAuthorizeTrait;

    public static function routesRegister(RoutingConfigurator $routes): void
    {
        static::controllerMiddlewareRegister('apiAuthorizeHandle');

        static::routesAdd($routes, ['OPTIONS', 'GET'], '/','actionHomeEmpty');

        static::routesAdd($routes, ['OPTIONS', 'GET', 'POST', 'PUT', 'DELETE', 'HEAD'], '/debug/test', 'actionTest');

        static::routesAdd($routes, ['OPTIONS', 'GET'], '/debug/dt-as-string', 'actionHomeDebugDateTimeAsString');
        static::routesAdd($routes, ['OPTIONS', 'GET'], '/debug/dt-as-array', 'actionHomeDebugDateTimeAsArray');
        static::routesAdd($routes, ['OPTIONS', 'GET'], '/debug/env', 'actionHomeDebugEnvAsArray');
        static::routesAdd($routes, ['OPTIONS', 'GET'], '/debug/server', 'actionHomeDebugServerAsArray');
        static::routesAdd($routes, ['OPTIONS', 'GET'], '/debug/session', 'actionHomeDebugSessionAsArray');
        static::routesAdd($routes, ['OPTIONS', 'GET'], '/debug/cookie', 'actionHomeDebugCookieAsArray');
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