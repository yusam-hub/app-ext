<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers\Api\Debug;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use YusamHub\AppExt\Exceptions\HttpAppExtRuntimeException;
use YusamHub\AppExt\Exceptions\HttpBadRequestAppExtRuntimeException;
use YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException;
use YusamHub\AppExt\SymfonyExt\Http\Controllers\BaseHttpController;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ControllerMiddlewareInterface;
use YusamHub\AppExt\SymfonyExt\Http\Traits\ApiAuthorizeTrait;
use YusamHub\AppExt\SymfonyExt\Http\Traits\ControllerMiddlewareTrait;

class DebugController extends BaseHttpController implements ControllerMiddlewareInterface
{
    use ControllerMiddlewareTrait;
    use ApiAuthorizeTrait;

    protected array $apiAuthorizePathExcludes = [
        '/api/debug',
        '/api/debug/test/exception'
    ];

    public static function routesRegister(RoutingConfigurator $routes): void
    {
        static::controllerMiddlewareRegister('apiAuthorizeHandle');

        static::routesAdd($routes, ['OPTIONS', 'GET'],'/api/debug', 'getApiHome');

        static::routesAdd($routes, ['OPTIONS', 'GET', 'POST'], '/api/debug/test/params', 'actionTestParams');

        static::routesAdd($routes, ['OPTIONS', 'GET'], '/api/debug/test/exception', 'actionTestException');

        static::routesAdd($routes, ['OPTIONS', 'POST'], '/api/debug/test/file', 'actionTestFile');


    }

    /**
     * @param Request $request
     * @return array
     */
    public function getApiHome(Request $request): array
    {
        return [];
    }


    /**
     * @OA\Get(
     *   tags={"Test"},
     *   path="/test/params",
     *   summary="GET params",
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
     *   summary="Post params",
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
            'method' => $request->getMethod(),
            'host' => $request->getHost(),
            'requestUri' => $request->getRequestUri(),
            'query' => $request->query->all(),
            'params' => $request->request->all(),
            'content' => $request->getContent(),
            'attributes' => $request->attributes->all(),
            'server' => $request->server->all(),
            'cookies' => $request->cookies->all(),
        ];
    }

    /**
     * @OA\Get(
     *   tags={"Test"},
     *   path="/test/exception",
     *   summary="GET exception",
     *   deprecated=false,
     *   @OA\Response(response=200, description="OK", @OA\MediaType(mediaType="application/json", @OA\Schema(
     *        @OA\Property(property="status", type="string", example="ok"),
     *        @OA\Property(property="data", type="array", example="array", @OA\Items(
     *        )),
     *        example={},
     *   ))),
     *   @OA\Response(response=400, description="Bad Request", @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ResponseErrorDefault"))),
     * );
     */

    /**
     * @param Request $request
     * @return array
     */
    public function actionTestException(Request $request): array
    {
        throw new HttpBadRequestAppExtRuntimeException([
            'field' => 'invalid'
        ]);
        return [];
    }

    /**
     * @OA\Post(
     *   tags={"Test"},
     *   path="/test/file",
     *   summary="Post file",
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
            'method' => $request->getMethod(),
            'host' => $request->getHost(),
            'requestUri' => $request->getRequestUri(),
            'query' => $request->query->all(),
            'params' => $request->request->all(),
            'attributes' => $request->attributes->all(),
            'server' => $request->server->all(),
            'cookies' => $request->cookies->all(),
            'files' => app_ext_get_files($request),
        ];
    }
}