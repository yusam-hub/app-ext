<?php

require __DIR__ . '/global-inc.php';

use YusamHub\AppExt\Logger\FileLogger;

$fileLogger = new FileLogger([
    'logDir' => __DIR__ . '/tmp',
    'fileMaxSize' => 1024,
    'level' => \Psr\Log\LogLevel::ERROR
]);

for($i=1;$i <= 100; $i++) {
    $fileLogger->debug("message-".$i, ["some-text"]);
    $fileLogger->info("message-".$i, ["some-text"]);
    $fileLogger->notice("message-".$i, ["some-text"]);
    $fileLogger->warning("message-".$i, ["some-text"]);
    $fileLogger->error("message-".$i, ["some-text"]);
    $fileLogger->critical("message-".$i, ["some-text"]);
    $fileLogger->alert("message-".$i, ["some-text"]);
    $fileLogger->emergency("message-".$i, ["some-text"]);
}
