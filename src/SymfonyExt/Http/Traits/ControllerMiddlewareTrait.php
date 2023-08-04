<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Traits;

use Symfony\Component\HttpFoundation\Request;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ControllerMiddlewareInterface;

trait ControllerMiddlewareTrait
{
    private static array $methodNames = [];

    public static function controllerMiddlewareRegister(string $class, string $methodName): void
    {
        if (!is_subclass_of($class, ControllerMiddlewareInterface::class)){
            throw new \RuntimeException(sprintf("Method register fail, missing interface of [ %s ] for class [ %s ]", ControllerMiddlewareInterface::class, $class));
        }
        static::$methodNames[] = $methodName;
    }

    public function controllerMiddlewareHandle(Request $request): void
    {
        foreach(static::$methodNames as $methodName) {
            if (method_exists($this, $methodName)) {
                call_user_func_array([$this, $methodName], [$request]);
            }
        }
    }
}