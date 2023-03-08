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
        //0 => 'testing',
    ]
    //'infoTitle' => 'Api %s Server',
    //'infoVersion' => '1.0.0',
];