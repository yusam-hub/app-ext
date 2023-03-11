<?php

require __DIR__ . '/../../global-inc.php';

use Symfony\Component\HttpFoundation\Request;

$symfonyRequest = Request::createFromGlobals();
$symfonyRequest->attributes->add([
    '_files' => $_FILES,
]);
$controllerKernel = new \YusamHub\AppExt\SymfonyExt\ControllerKernel(
    app_ext_config('routes.path'),
    $symfonyRequest,
    app_ext_config('routes.default')
);
$controllerKernel->setLogger(app_ext_logger('app'));
$controllerKernel->runIndex();
