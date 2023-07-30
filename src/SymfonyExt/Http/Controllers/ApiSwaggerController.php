<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use YusamHub\AppExt\Api\OpenApiExt;
use YusamHub\AppExt\Api\SwaggerUiExt;

abstract class ApiSwaggerController extends BaseHttpController
{
    /**
     * @return array
     */
    protected static function getSwaggerModules(): array
    {
        return [];
    }

    /**
     * @param RoutingConfigurator $routes
     * @return void
     */
    public static function routesRegister(RoutingConfigurator $routes): void
    {
        static::routesAdd($routes, ['OPTIONS', 'GET'],app_ext_config('api.publicSwaggerUiUri') . '/{module}', 'getSwaggerUiHtml', [
            'module' => '^'.implode('|', static::getSwaggerModules()).'$'
        ]);
        static::routesAdd($routes, ['OPTIONS', 'GET'],app_ext_config('api.publicSwaggerUiUri') . '/{module}/open-api', 'getSwaggerUiOpenApi', [
            'module' => '^'.implode('|', static::getSwaggerModules()).'$'
        ]);
    }

    /**
     * @param Request $request
     * @param string $module
     * @return string[]
     */
    protected function getOpenApiScanPaths(Request $request, string $module): array
    {
        return [
            __DIR__ . DIRECTORY_SEPARATOR . ucfirst($module)
        ];
    }

    /**
     * @return string
     */
    protected function getPublicSwaggerUiDir(): string
    {
        return app_ext_config('api.publicSwaggerUiDir');
    }

    /**
     * @return string
     */
    protected function getPublicSwaggerUiUri(): string
    {
        return app_ext_config('api.publicSwaggerUiUri');
    }

    /**
     * @param Request $request
     * @param string $module
     * @return array
     */
    protected function getReplaceKeyValuePairForModule(Request $request, string $module): array
    {
        $port = (int) ($request->server->get('SERVER_PORT') ?? $request->getPort());
        if (in_array($port, [80,443])) {
            $port = 0;
        }
        return [
            '__OA_INFO_TITLE__' => sprintf(app_ext_config('api.infoTitle','Api %s Server'), ucfirst($module)),
            '__OA_INFO_VERSION__' => app_ext_config('api.infoVersion', '1.0.0'),
            '__OA_SERVER_HOSTNAME__' => $request->getHost() . ($port ? ':'.$port : ''),
            '__OA_SERVER_PATH__' => trim(app_ext_config('api.apiBaseUri'), '/') . '/' . strtolower($module),
            '__OA_SERVER_SCHEMA__' => $request->getScheme(),
            '__OA_SECURITY_SCHEME_TOKEN_HEADER_NAME__' => app_ext_config('api.tokenKeyName', 'X-Token'),
            '__OA_SECURITY_SCHEME_SIGN_HEADER_NAME__' => app_ext_config('api.signKeyName', 'X-Sign'),
            '__OA_METHOD_GET_HOME_PATH__' => '/',
        ];
    }

    /**
     * @param Request $request
     * @param string $module
     * @return string
     */
    public function getSwaggerUiOpenApi(Request $request, string $module): string
    {
        $openApiExt = new OpenApiExt([
            'paths' => $this->getOpenApiScanPaths($request, $module),
            'replaceKeyValuePair' => $this->getReplaceKeyValuePairForModule($request, $module)
        ]);

        try {
            //todo: use cache for production
            return $openApiExt->generateOpenApi();

        } catch (\Throwable $e) {

            $this->error($e->getMessage());

            return '{}';
        }
    }

    /**
     * @param Request $request
     * @param string $module
     * @return string
     */
    public function getSwaggerUiHtml(Request $request, string $module): string
    {
        return SwaggerUiExt::replaceIndexHtml($this->getPublicSwaggerUiDir(), $this->getPublicSwaggerUiUri(), sprintf('/%s/open-api', strtolower($module)));
    }
}