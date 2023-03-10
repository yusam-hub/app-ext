<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Db;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;
class DbCheckCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('db:check')
            ->setDescription('db:check:description')
            ->setHelp('db:check:help')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("");

        $phpDt = date(DATE_TIME_APP_EXT_FORMAT);
        $output->writeln($this->tagGreen(sprintf('PHP: %s',$phpDt)));
        $output->writeln($this->tagGreen(sprintf('CONNECTION DEFAULT: %s', db()->getDefaultConnectionName())));
        $out = [];

        foreach(db()->getConnectionNames() as $connectionName)
        {
            try {

                $row = db()->{$connectionName}->fetchOne('SELECT NOW() as dt, DATABASE() as dbName');

                $out[] = [
                    $connectionName, $row['dbName'], ($row['dt'] != $phpDt) ? $this->tagYellow($row['dt']) : $row['dt'], ($row['dt'] != $phpDt) ? "Invalid date time between mysql & php" : $this->tagGreen('SUCCESS')
                ];

            } catch (\Throwable $e) {

                $out[] = [
                    $connectionName, '-', '-', $this->tagRed('ERROR: ' . $e->getMessage())
                ];

            }
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Connection', 'Db Name','DateTime','Info'])
            ->setRows($out)
        ;
        $table->render();

        return self::SUCCESS;
    }
}