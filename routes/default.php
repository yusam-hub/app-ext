<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    \YusamHub\AppExt\SymfonyExt\Http\Controllers\HomeController::routesRegister($routes);
    \YusamHub\AppExt\SymfonyExt\Http\Controllers\Api\ApiSwaggerController::routesRegister($routes);
    \YusamHub\AppExt\SymfonyExt\Http\Controllers\Api\Debug\DebugController::routesRegister($routes);
};