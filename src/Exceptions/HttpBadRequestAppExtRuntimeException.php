<?php

namespace YusamHub\AppExt\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class HttpBadRequestAppExtRuntimeException extends HttpAppExtRuntimeException
{
    protected int $statusCode = Response::HTTP_BAD_REQUEST;

}