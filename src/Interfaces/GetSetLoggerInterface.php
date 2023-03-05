<?php

namespace YusamHub\AppExt\Interfaces;

use Psr\Log\LoggerInterface;

interface GetSetLoggerInterface extends LoggerInterface
{
    public function getLogger(): ?LoggerInterface;
    public function setLogger(?LoggerInterface $logger): void;
}