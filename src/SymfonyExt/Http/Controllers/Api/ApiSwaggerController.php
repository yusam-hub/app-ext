<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Request;

class ApiSwaggerController extends \YusamHub\AppExt\SymfonyExt\Http\Controllers\ApiSwaggerController
{
    protected static function getSwaggerModules(): array
    {
        return [
            'debug',
        ];
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

}