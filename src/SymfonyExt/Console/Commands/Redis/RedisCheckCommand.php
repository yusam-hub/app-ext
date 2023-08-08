<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Redis;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;
class RedisCheckCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('redis:check')
            ->setDescription('redis:check:description')
            ->setHelp('redis:check:help')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("");

        $output->writeln($this->tagGreen(sprintf('CONNECTION DEFAULT: %s', app_ext_redis_global()->getDefaultConnectionName())));
        $out = [];

        foreach(app_ext_redis_global()->getConnectionNames() as $connectionName)
        {
            try {

                $isConnected = app_ext_redis_global()->connection($connectionName)->redis()->isConnected();

                $out[] = [
                    $connectionName, $isConnected ? $this->tagGreen('SUCCESS') : $this->tagRed('FAIL')
                ];

            } catch (\Throwable $e) {

                $out[] = [
                    $connectionName, $this->tagRed('ERROR: ' . $e->getMessage())
                ];

            }
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Connection', 'Info'])
            ->setRows($out)
        ;
        $table->render();

        return self::SUCCESS;
    }
}