<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface ControllerMiddlewareInterface
{
    public static function controllerMiddlewareRegister(string $class, string $methodName): void;
    public function controllerMiddlewareHandle(Request $request): void;
}