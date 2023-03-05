<?php

namespace YusamHub\AppExt\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait GetSetConsoleTrait
{
    protected ?InputInterface $consoleInput = null;
    protected ?OutputInterface $consoleOutput = null;

    public function getConsoleInput(): ?InputInterface
    {
        return $this->consoleInput;
    }

    public function setConsoleInput(?InputInterface $input): void
    {
        $this->consoleInput = $input;
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