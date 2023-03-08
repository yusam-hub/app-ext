<?php

return [
    'publicSwaggerUiDir' => app()->getPublicDir('/swagger-ui'),
    'publicSwaggerUiUri' => '/swagger-ui',
    'apiBaseUri' => '/api',
    'tokenKeyName' => 'X-Token',
    'signKeyName' => 'X-Sign',
    'tokens' => [
        'testing' => 0
    ],
    'signs' => [
        0 => 'testing',
    ],
    'signHandle' => function(\Symfony\Component\HttpFoundation\Request $request, int $apiAuthorizedId, string $apiAuthorizedSign) {
        $signValue = (string) $request->headers->get(app_ext_config('api.signKeyName'));
        if ($signValue !== $apiAuthorizedSign) {
            throw new \YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException([
                'message' => 'Invalid sign value',
                'authorizedId' => $apiAuthorizedId
            ]);
        }
    }
    //'infoTitle' => 'Api %s Server',
    //'infoVersion' => '1.0.0',
];