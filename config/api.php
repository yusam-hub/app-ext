<?php

use YusamHub\DbExt\Interfaces\PdoExtModelInterface;

return [
    'publicSwaggerUiDir' => app_ext()->getPublicDir('/swagger-ui'),
    'publicSwaggerUiUri' => '/swagger-ui',
    'apiBaseUri' => '/api',
    'tokenKeyName' => 'X-Token',
    'signKeyName' => 'X-Sign',
    'debugTokens' => [
        'testing' => 0 //по токену находим ID
    ],
    'debugSigns' => [
        0 => 'testing', //по ID находим ключ подписи для ID
    ],
    'tokenHandle' => function(
        \YusamHub\AppExt\Traits\Interfaces\GetSetHttpControllerInterface $httpController,
        \Symfony\Component\HttpFoundation\Request $request
    )
    {
        throw new \YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException([
            'detail' => 'Invalid token value'
        ]);
    },
    'signHandle' => function(
        \YusamHub\AppExt\Traits\Interfaces\GetSetHttpControllerInterface $httpController,
        \Symfony\Component\HttpFoundation\Request $request,
        int $apiAuthorizedId,
        $model
    )
    {
        throw new \YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException([
            'detail' => 'Invalid sign value',
        ]);
    },
    //'infoTitle' => 'Api %s Server',
    //'infoVersion' => '1.0.0',
];