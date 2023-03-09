<?php

return [
    'default' => 'mysql',

    'connections' => [
        'mysql' => [
            'user' => app_ext_env('DATABASE_USER','root'),
            'password' => app_ext_env('DATABASE_PASSWORD', 'Qwertyu1'),
            'host' => app_ext_env('DATABASE_HOST', 'localhost'),
            'port' => app_ext_env('DATABASE_PORT', 3306),
            'dbName' => app_ext_env('DATABASE_DBNAME','localhost'),
        ],
        'mysql2' => [
            'user' => app_ext_env('DATABASE_USER','root'),
            'password' => app_ext_env('DATABASE_PASSWORD', 'Qwertyu1'),
            'host' => app_ext_env('DATABASE_HOST', 'localhost'),
            'port' => app_ext_env('DATABASE_PORT', 3306),
            'dbName' => app_ext_env('DATABASE_DBNAME','localhost'),
        ],
        'mysql3' => [
            'user' => app_ext_env('DATABASE_USER','root'),
            'password' => app_ext_env('DATABASE_PASSWORD', 'Qwertyu1'),
            'host' => app_ext_env('DATABASE_HOST', 'localhost'),
            'port' => app_ext_env('DATABASE_PORT', 3306),
            'dbName' => app_ext_env('DATABASE_DBNAME','localhost'),
        ],
    ],
];
