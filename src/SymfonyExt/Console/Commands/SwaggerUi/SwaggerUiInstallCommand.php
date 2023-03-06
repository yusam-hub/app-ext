<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\SwaggerUi;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\Api\OpenApiExt;
use YusamHub\AppExt\Api\SwaggerUiExt;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;

class SwaggerUiInstallCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('swagger-ui:install')
            ->setDescription('Copy swagger-ui files to public folder')
            ->addOption('vendor', null, InputOption::VALUE_OPTIONAL, 'Vendor path', './vendor')
            ->addOption('public', null,InputOption::VALUE_OPTIONAL, 'Public path', './tmp/public/swagger-ui')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $vendor = strval($input->getOption('vendor'));
        $public = strval($input->getOption('public'));

        $output->writeln(sprintf('<yellow>--vendor=%s</yellow>', $vendor));
        $output->writeln(sprintf('<yellow>--public=%s</yellow>', $public));

        try {

            $out = SwaggerUiExt::install($vendor, $public);

            foreach($out as $file) {
                $output->writeln(sprintf('<green>Copied: %s</green>', $file));
            }

        } catch (\Throwable $e) {

            $output->writeln(sprintf('<red>Error: %s</red>', $e->getMessage()));

        }

        return self::SUCCESS;
    }
}