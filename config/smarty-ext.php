<?php

return [
    'templateDefault' => 'front',

    'templates' => [
        'front' => [
            'smarty' => [
                'debugging' => true,
                'force_compile' => true,
                'caching' => false,
                'cache_lifetime' => 120,
            ],
            'smartyDirs' => [
                'pluginDir' => __DIR__ .'/../resources/smarty_plugins',
                'templateDir' => __DIR__ .'/../resources/views',
                'configDir' => __DIR__ .'/../resources/views',
                'compileDir' => __DIR__ .'/../storage/smarty/compiles',
                'cacheDir' => __DIR__ .'/../storage/smarty/caches',
            ],
            'smartyExt' => [
                'extension' => '.tpl',
                'vendorDir' => __DIR__ . '/../vendor',
            ],
        ],
    ],

    'debugSmartyException' => true,
];
