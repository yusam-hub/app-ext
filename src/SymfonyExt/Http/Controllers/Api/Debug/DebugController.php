<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers\Api\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use YusamHub\AppExt\SymfonyExt\Http\Controllers\BaseHttpController;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ControllerMiddlewareInterface;
use YusamHub\AppExt\SymfonyExt\Http\Traits\ApiAuthorizeTrait;
use YusamHub\AppExt\SymfonyExt\Http\Traits\ControllerMiddlewareTrait;

class DebugController extends BaseHttpController implements ControllerMiddlewareInterface
{
    use ControllerMiddlewareTrait;
    use ApiAuthorizeTrait;

    public static function routesRegister(RoutingConfigurator $routes): void
    {
        static::controllerMiddlewareRegister('apiAuthorizeHandle');

        static::routesAdd($routes, ['OPTIONS', 'GET', 'POST'], '/api/debug/test/params', 'actionTestParams');
        static::routesAdd($routes, ['OPTIONS', 'POST'], '/api/debug/test/file', 'actionTestFile');

        static::routesAdd($routes, ['OPTIONS', 'GET'], '/api/debug/dt-as-string', 'actionDateTimeAsString');
        static::routesAdd($routes, ['OPTIONS', 'GET'], '/api/debug/dt-as-array', 'actionDateTimeAsArray');
        static::routesAdd($routes, ['OPTIONS', 'GET'], '/api/debug/env', 'actionEnvAsArray');
        static::routesAdd($routes, ['OPTIONS', 'GET'], '/api/debug/server', 'actionServerAsArray');
        static::routesAdd($routes, ['OPTIONS', 'GET'], '/api/debug/session', 'actionSessionAsArray');
        static::routesAdd($routes, ['OPTIONS', 'GET'], '/api/debug/cookie', 'actionCookieAsArray');
    }

    /**
     * @OA\Get(
     *   tags={"Test"},
     *   path="/test/params",
     *   summary="GET",
     *   security={{"XTokenScheme":{}},{"XSignScheme":{}}},
     *   deprecated=false,
     *   @OA\Parameter(name="Test-Header-Value",
     *     in="header",
     *     required=false,
     *     example="",
     *     description="",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="testQueryValue",
     *     in="query",
     *     required=false,
     *     example="",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response=200, description="OK", @OA\MediaType(mediaType="application/json", @OA\Schema(
     *        @OA\Property(property="status", type="string", example="ok"),
     *        @OA\Property(property="data", type="array", example="array", @OA\Items(
     *        )),
     *        example={},
     *   ))),
     *   @OA\Response(response=401, description="Unauthorized", @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ResponseErrorDefault"))),
     * );
     */

    /**
     * @OA\Post(
     *   tags={"Test"},
     *   path="/test/params",
     *   summary="Post",
     *   security={{"XTokenScheme":{}},{"XSignScheme":{}}},
     *   deprecated=false,
     *   @OA\Parameter(name="Test-Header-Value",
     *     in="header",
     *     required=false,
     *     example="",
     *     description="",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="testQueryValue",
     *     in="query",
     *     required=false,
     *     example="",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\RequestBody(required=false,
     *      @OA\MediaType(
     *        mediaType="multipart/form-data",
     *        @OA\Schema(
     *            @OA\Property(property="testKey1", type="string", description=""),
     *            @OA\Property(property="testKey2", type="string", description=""),
     *            @OA\Property(property="testKey3", type="string", description=""),
     *        )
     *      )
     *   ),
     *   @OA\Response(response=200, description="OK", @OA\MediaType(mediaType="application/json", @OA\Schema(
     *        @OA\Property(property="status", type="string", example="ok"),
     *        @OA\Property(property="data", type="array", example="array", @OA\Items(
     *        )),
     *        example={},
     *   ))),
     *   @OA\Response(response=400, description="Bad Request", @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ResponseErrorDefault"))),
     *   @OA\Response(response=401, description="Unauthorized", @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ResponseErrorDefault"))),
     * );
     */

    public function actionTestParams(Request $request): array
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
     * @OA\Post(
     *   tags={"Test"},
     *   path="/test/file",
     *   summary="Post file",
     *   security={{"XTokenScheme":{}}},
     *   deprecated=false,
     *   @OA\RequestBody(required=true,
     *      @OA\MediaType(
     *        mediaType="multipart/form-data",
     *        @OA\Schema(
     *            @OA\Property(property="file", type="string", format="binary", description="Any file"),
     *        )
     *      )
     *   ),
     *   @OA\Response(response=200, description="OK", @OA\MediaType(mediaType="application/json", @OA\Schema(
     *        @OA\Property(property="status", type="string", example="ok"),
     *        @OA\Property(property="data", type="array", example="array", @OA\Items(
     *        )),
     *        example={},
     *   ))),
     *   @OA\Response(response=400, description="Bad Request", @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ResponseErrorDefault"))),
     *   @OA\Response(response=401, description="Unauthorized", @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ResponseErrorDefault"))),
     * );
     */

    public function actionTestFile(Request $request): array
    {
        return [
            'dateTime' => date("Y-m-d H:i:s"),
            'method' => $request->getMethod(),
            'host' => $request->getHost(),
            'requestUri' => $request->getRequestUri(),
            'query' => $request->query->all(),
            'params' => $request->request->all(),
            'files' => $request->files->all()
        ];
    }

    /**
     * @param Request $request
     * @return string
     */
    public function actionDateTimeAsString(Request $request): string
    {
        return date("Y-m-d H:i:s");
    }

    /**
     * @param Request $request
     * @return array
     */
    public function actionDateTimeAsArray(Request $request): array
    {
        return [
            date("Y-m-d H:i:s")
        ];
    }

    public function actionEnvAsArray(Request $request): array
    {
        return (array) $_ENV;
    }

    public function actionServerAsArray(Request $request): array
    {
        return (array) $_SERVER;
    }

    public function actionSessionAsArray(Request $request): array
    {
        return (array) $_SESSION;
    }

    public function actionCookieAsArray(Request $request): array
    {
        return (array) $_COOKIE;
    }

}