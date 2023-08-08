<?php

namespace YusamHub\AppExt\Helpers;

class ExceptionHelper
{
    public static function e2a(\Throwable $e, bool $includeTrace = true): array
    {
        return [
            'errorFile' => $e->getFile() . ':' . $e->getLine(),
            'errorCode' => $e->getCode(),
            'errorTrace' => $includeTrace ? $e->getTrace() : null
        ];
    }


}