<?php

namespace YusamHub\AppExt\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpUnauthorizedAppExtRuntimeException extends HttpAppExtRuntimeException
{
    protected int $statusCode = Response::HTTP_UNAUTHORIZED;

}