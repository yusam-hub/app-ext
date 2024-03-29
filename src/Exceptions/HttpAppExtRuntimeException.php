<?php

namespace YusamHub\AppExt\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use YusamHub\AppExt\Exceptions\Interfaces\HttpAppExtRuntimeExceptionInterface;

class HttpAppExtRuntimeException extends AppExtRuntimeException implements HttpAppExtRuntimeExceptionInterface
{
    protected int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public function __construct(array $data = [], string $message = '', \Throwable $previous = null)
    {
        if (empty($message)) {
            $message = Response::$statusTexts[$this->statusCode];
        }
        parent::__construct($message, $data, 0, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}