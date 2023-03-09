<?php

namespace YusamHub\AppExt\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpForbiddenAppExtRuntimeException extends HttpAppExtRuntimeException
{
    protected int $statusCode = Response::HTTP_FORBIDDEN;

}