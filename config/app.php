<?php

return [
    'isDebugging' => (bool) app_ext_env('APP_IS_DEBUGGING', false),
    'rootDir' => realpath(__DIR__ .'/../'),
    'appDir' => false,
    'databaseDir' => false,
    'publicDir' => realpath(__DIR__ .'/../tmp/public'),
    'storageDir' => realpath(__DIR__ .'/../tmp'),
    'routesDir' => false,
];
