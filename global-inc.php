<?php

if (!defined('YUSAM_HUB_IS_DEBUGGING')) {
    define('YUSAM_HUB_IS_DEBUGGING', true);
}

if (!defined('YUSAM_HUB_DEBUG_LOG_DIR')) {
    define('YUSAM_HUB_DEBUG_LOG_DIR', realpath(__DIR__ . "/storage/logs"));
}

require __DIR__ . '/vendor/autoload.php';

\YusamHub\AppExt\Config::instance(__DIR__ . '/config');
\YusamHub\AppExt\Env::instance(__DIR__ . '/env');