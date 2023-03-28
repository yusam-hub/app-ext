<?php

return [
    'connectionDefault' => 'default',

    'connections' => [
        'default' => [
            'user' => app_ext_env('DATABASE_USER','root'),
            'password' => app_ext_env('DATABASE_PASSWORD', 'Qwertyu1'),
            'host' => app_ext_env('DATABASE_HOST', 'localhost'),
            'port' => app_ext_env('DATABASE_PORT', 3306),
            'dbName' => app_ext_env('DATABASE_DBNAME','localhost'),
        ],
    ],

    'migrations' => [
        'paths' => [
            app_ext()->getDatabaseDir('/migrations'),
        ],
        'savedDir' => app_ext()->getStorageDir('/app/migrations')
    ],
];
