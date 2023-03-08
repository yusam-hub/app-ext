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
        static::routesAdd($routes, ['OPTIONS', 'GET'],'/swagger-ui/{module}', 'getSwaggerUiHtml', [
            'module' => '^'.implode('|', static::getSwaggerModules()).'$'
        ]);
        static::routesAdd($routes, ['OPTIONS', 'GET'],'/swagger-ui/{module}/open-api', 'getSwaggerUiOpenApi', [
            'module' => '^'.implode('|', static::getSwaggerModules()).'$'
        ]);
    }


    /**
     * @param Request $request
     * @param string $module
     * @return string
     */
    public function getSwaggerUiOpenApi(Request $request, string $module): string
    {

        $openApiExt = new OpenApiExt([
            'paths' => [
                __DIR__ . DIRECTORY_SEPARATOR . ucfirst($module)
            ],
            'replaceKeyValuePair' => [
                '__OA_INFO_TITLE__' => sprintf('Api %s Server', ucfirst($module)),
                '__OA_INFO_VERSION__' => '1.0.0',
                '__OA_SERVER_HOSTNAME__' => $request->getHost(),
                '__OA_SERVER_PATH__' => '/api/' . strtolower($module),
                '__OA_SERVER_SCHEMA__' => $request->getScheme(),
                '__OA_SECURITY_SCHEME_TOKEN_HEADER_NAME__' => 'X-Token',
                '__OA_SECURITY_SCHEME_SIGN_HEADER_NAME__' => 'X-Sign',
                '__OA_METHOD_GET_HOME_PATH__' => '/',
            ]
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
        return SwaggerUiExt::replaceIndexHtml(app()->getPublicDir('/swagger-ui') , '/swagger-ui',sprintf('/%s/open-api', strtolower($module)));
    }
}