<?php

return [
    'isDebugging' => (bool) app_ext_env('APP_IS_DEBUGGING', false),
    'rootDir' => realpath(__DIR__ .'/../'),
    'appDir' => realpath(__DIR__ .'/../src'),
    'databaseDir' => realpath(__DIR__ .'/../database'),
    'publicDir' => realpath(__DIR__ .'/../tmp/public'),
    'storageDir' => realpath(__DIR__ .'/../tmp'),
    'routesDir' => realpath(__DIR__ .'/../routes'),
];
