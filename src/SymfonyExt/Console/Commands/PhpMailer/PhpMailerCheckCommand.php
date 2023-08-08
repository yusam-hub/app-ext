<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\PhpMailer;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;
class PhpMailerCheckCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('php-mailer:check')
            ->setDescription('php-mailer:check:description')
            ->setHelp('php-mailer:check:help')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("");

        $output->writeln($this->tagGreen(sprintf('CONNECTION DEFAULT: %s', app_ext_php_mailer_global()->getDefaultConnectionName())));
        $out = [];

        foreach(app_ext_php_mailer_global()->getConnectionNames() as $connectionName)
        {
            try {

                $isSent = app_ext_php_mailer_global()->connection($connectionName)->sendTo(
                    app_ext_php_mailer_global()->connection($connectionName)->getFromAddress(), 'check','check'
                );

                $out[] = [
                    $connectionName, $isSent ? $this->tagGreen('SUCCESS') : $this->tagRed('FAIL')
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