<?php

namespace YusamHub\AppExt\Traits\Interfaces;

use Symfony\Component\Console\Output\OutputInterface;

interface GetSetConsoleInterface
{
    public function hasConsoleOutput(): bool;
    public function getConsoleOutput(): ?OutputInterface;
    public function setConsoleOutput(?OutputInterface $output): void;
}