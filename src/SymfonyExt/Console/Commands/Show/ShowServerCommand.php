<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Show;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;

class ShowServerCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('show:server')
            ->setDescription('show:server:description')
            ->setHelp('show:server:help')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        print_r($_SERVER);
        return self::SUCCESS;
    }
}