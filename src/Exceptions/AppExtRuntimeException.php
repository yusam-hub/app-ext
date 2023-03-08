<?php

namespace YusamHub\AppExt\Exceptions;

use YusamHub\AppExt\Exceptions\Interfaces\AppExtRuntimeExceptionInterface;

class AppExtRuntimeException extends \RuntimeException implements AppExtRuntimeExceptionInterface
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @param string $message
     * @param array $data
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", array $data = [], int $code = 0, \Throwable $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}