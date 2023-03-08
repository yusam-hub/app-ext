<?php

return [
    'default' => 'default',

    'connections' => [
        'default' => [
            'host' => app_ext_env('REDIS_HOST', 'localhost'),
            'port' => app_ext_env('REDIS_PORT', 6379),
            'dbIndex' => 0,
            'prefix' => 'miniapp_',
        ],
    ],
];
