<?php

return [
    'isDebugging' => (bool) app_ext_env('APP_IS_DEBUGGING', false),
    'rootDir' => realpath(__DIR__ .'/../'),
    'databaseDir' => realpath(__DIR__ .'/../database'),
    'publicDir' => realpath(__DIR__ .'/../public'),
    'storageDir' => realpath(__DIR__ .'/../storage'),
    'routesDir' => realpath(__DIR__ .'/../routes'),
];
