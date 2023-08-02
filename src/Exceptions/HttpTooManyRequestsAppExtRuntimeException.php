<?php

namespace YusamHub\AppExt\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpTooManyRequestsAppExtRuntimeException extends HttpAppExtRuntimeException
{
    protected int $statusCode = Response::HTTP_TOO_MANY_REQUESTS;

}