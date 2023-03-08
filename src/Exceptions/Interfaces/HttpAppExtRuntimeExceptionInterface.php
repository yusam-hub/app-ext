<?php

namespace YusamHub\AppExt\Exceptions\Interfaces;

interface HttpAppExtRuntimeExceptionInterface extends AppExtRuntimeExceptionInterface
{
    public function getStatusCode(): int;
}