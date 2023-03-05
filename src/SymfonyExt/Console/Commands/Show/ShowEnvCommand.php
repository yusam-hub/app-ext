<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Show;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;

class ShowEnvCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('show:env')
            ->setDescription('show:env:description')
            ->setHelp('show:env:help')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        print_r($_ENV);
        return self::SUCCESS;
    }
}