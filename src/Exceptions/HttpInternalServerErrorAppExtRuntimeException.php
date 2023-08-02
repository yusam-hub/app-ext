<?php

namespace YusamHub\AppExt\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpInternalServerErrorAppExtRuntimeException extends HttpAppExtRuntimeException
{
    protected int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

}