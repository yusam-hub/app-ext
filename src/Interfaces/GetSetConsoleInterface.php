<?php

namespace YusamHub\AppExt\Interfaces;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface GetSetConsoleInterface
{
    public function getConsoleInput(): ?InputInterface;
    public function setConsoleInput(?InputInterface $input): void;
    public function getConsoleOutput(): ?OutputInterface;
    public function setConsoleOutput(?OutputInterface $output): void;
}