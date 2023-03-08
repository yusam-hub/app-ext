<?php

namespace YusamHub\AppExt\SymfonyExt;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use YusamHub\AppExt\SymfonyExt\Http\Controllers\BaseHttpController;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ControllerMiddlewareInterface;

class ControllerResolverKernel extends ControllerResolver
{
    protected ControllerKernel $controllerKernel;
    protected Request $request;

    /**
     * @param ControllerKernel $controllerKernel
     */
    public function __construct(ControllerKernel $controllerKernel, Request $request)
    {
        $this->controllerKernel = $controllerKernel;
        $this->request = $request;
        parent::__construct(null);
    }

    /**
     * @param string $class
     * @return mixed|object|string|BaseHttpController
     */
    protected function instantiateController(string $class)
    {
        $controller = parent::instantiateController($class);

        if ($controller instanceof BaseHttpController) {
            $controller->setLogger($this->controllerKernel->getLogger());
        }

        if ($controller instanceof ControllerMiddlewareInterface) {
            $controller->controllerMiddlewareHandle($this->request);
        }

        return $controller;
    }
}