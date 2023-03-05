<?php

namespace YusamHub\AppExt\Interfaces;

use Psr\Log\LoggerInterface;

interface GetSetLoggerInterface extends LoggerInterface
{
    public function getLogger(): ?LoggerInterface;
    public function setLogger(?LoggerInterface $logger): void;
    public function getConsoleOutputEnabled(): bool;
    public function setConsoleOutputEnabled(bool $value): void;
}