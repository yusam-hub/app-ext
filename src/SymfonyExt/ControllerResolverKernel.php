<?php

namespace YusamHub\AppExt\SymfonyExt;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use YusamHub\AppExt\SymfonyExt\Http\Controllers\BaseHttpController;

class ControllerResolverKernel extends ControllerResolver
{
    protected ControllerKernel $controllerKernel;
    public function __construct(ControllerKernel $controllerKernel)
    {
        $this->controllerKernel = $controllerKernel;
        parent::__construct(null);
    }

    protected function instantiateController(string $class)
    {
        $controller = parent::instantiateController($class);
        if ($controller instanceof BaseHttpController) {
            $controller->setLogger($this->controllerKernel->getLogger());
        }

        return $controller;
    }
}