<?php

namespace YusamHub\AppExt\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait GetSetConsoleTrait
{
    private ?InputInterface $input = null;
    private ?OutputInterface $output = null;

    public function getConsoleInput(): ?InputInterface
    {
        return $this->input;
    }

    public function setConsoleInput(?InputInterface $input): void
    {
        $this->input = $input;
    }

    public function getConsoleOutput(): ?OutputInterface
    {
        return $this->output;
    }

    public function setConsoleOutput(?OutputInterface $output): void
    {
        $this->output = $output;
    }
}