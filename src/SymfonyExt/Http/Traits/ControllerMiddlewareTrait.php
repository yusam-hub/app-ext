<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Traits;

use Symfony\Component\HttpFoundation\Request;
trait ControllerMiddlewareTrait
{
    private static array $methodNames = [];

    public static function controllerMiddlewareRegister(string $class, string $methodName): void
    {
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