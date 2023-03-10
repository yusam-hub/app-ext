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
            foreach (db()->getConnectionNames() as $connectionName) {
                $migrations = new PdoExtMigrations(
                    db()->{$connectionName},
                    $migrationPath . '/' . $connectionName,
                    app_ext_config('database.migrations.savedDir') . '/migrations_' . $connectionName . '.lst'
                );
                $migrations->setEchoLineClosure(function (string $message) use ($output) {
                    $output->writeln($message);
                });
                $migrations->migrate();
            }
        }

        return self::SUCCESS;
    }
}