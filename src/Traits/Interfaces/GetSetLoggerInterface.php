<?php

namespace YusamHub\AppExt\Traits\Interfaces;

use Psr\Log\LoggerInterface;

interface GetSetLoggerInterface extends LoggerInterface
{
    public function hasLogger(): bool;
    public function getLogger(): ?LoggerInterface;
    public function setLogger(?LoggerInterface $logger): void;
    public function getLoggerConsoleOutputEnabled(): bool;
    public function setLoggerConsoleOutputEnabled(bool $value): void;
}