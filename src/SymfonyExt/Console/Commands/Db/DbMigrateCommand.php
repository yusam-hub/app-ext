<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Db;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;
use YusamHub\DbExt\PdoExtMigrations;
class DbMigrateCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('db:migrate')
            ->setDescription('db:migrate:description')
            ->setHelp('db:migrate:help')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $paths = (array) app_ext_config('database.migrations.paths');
        foreach($paths as $migrationPath) {

            foreach (app_ext_db_global()->getConnectionNames() as $connectionName) {

                $output->writeln($this->tagYellow(sprintf("%s/%s", $migrationPath, $connectionName)));
                $output->writeln('');

                $migrations = new PdoExtMigrations(
                    app_ext_db_global()->pdoExt($connectionName),
                    $migrationPath . '/' . $connectionName,
                    app_ext_config('database.migrations.savedDir') . '/migrations_' . $connectionName . '.lst'
                );

                $migrations->setEchoLineClosure(function(string $level, string $message) use ($output) {
                    if ($level === 'ERROR') {
                        $output->writeln($this->tagRed($message));
                    } else {
                        $output->writeln($this->tagGreen($message));
                    }
                });

                $migrations->migrate();
            }
        }

        return self::SUCCESS;
    }
}