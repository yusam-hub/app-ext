<?php

namespace YusamHub\AppExt\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait GetSetConsoleTrait
{
    private ?OutputInterface $consoleOutput = null;

    public function hasConsoleOutput(): bool
    {
        return !is_null($this->consoleOutput);
    }

    public function getConsoleOutput(): ?OutputInterface
    {
        return $this->consoleOutput;
    }

    public function setConsoleOutput(?OutputInterface $output): void
    {
        $this->consoleOutput = $output;
    }
}