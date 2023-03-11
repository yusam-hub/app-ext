<?php

namespace YusamHub\AppExt\SymfonyExt;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use YusamHub\AppExt\SymfonyExt\Http\Controllers\BaseHttpController;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ControllerMiddlewareInterface;
use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetLoggerInterface;

class ControllerResolverKernel
    extends ControllerResolver
    implements
    GetSetLoggerInterface,
    GetSetConsoleInterface
{
    use GetSetConsoleTrait;
    use GetSetLoggerTrait;
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

        if ($controller instanceof GetSetLoggerInterface) {
            $controller->setLogger($this->controllerKernel->getLogger());
            $controller->setLoggerConsoleOutputEnabled($this->controllerKernel->getLoggerConsoleOutputEnabled());
        }

        if ($controller instanceof GetSetConsoleInterface) {
            $controller->setConsoleOutput($this->controllerKernel->getConsoleOutput());
        }

        if ($controller instanceof ControllerMiddlewareInterface) {
            $controller->controllerMiddlewareHandle($this->request);
        }

        return $controller;
    }
}