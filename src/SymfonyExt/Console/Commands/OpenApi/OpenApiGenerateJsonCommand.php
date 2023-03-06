<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\OpenApi;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\Api\OpenApiExt;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;

class OpenApiGenerateJsonCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('open-api:generate-json')
            ->setDescription('Generate json file from controllers path where includes @OA')
            ->addOption('scan', null, InputOption::VALUE_OPTIONAL, 'Scan controllers path where includes @OA', './tmp/')
            ->addOption('file', null,InputOption::VALUE_OPTIONAL, 'File for save generated json', './tmp/open-api.json')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $scan = $input->getOption('scan');
        $file = $input->getOption('file');
        $output->writeln(sprintf('<yellow>--scan=%s</yellow>', $scan));
        $output->writeln(sprintf('<yellow>--file=%s</yellow>', $file));

        try {
            if (file_exists($file)) {
                @unlink($file);
            }

            $openApiExt = new OpenApiExt([
                'paths' => [
                    $scan
                ]
            ]);

            $json = $openApiExt->generateOpenApi();

            if (!empty($file)) {
                file_put_contents($file, $json);
            }

            if (file_exists($file)) {
                $output->writeln(sprintf('<green>File [%s] generated successfully</green>', $file));
            }
        } catch (\Throwable $e) {

            $output->writeln(sprintf('<red>Error: %s</red>', $e->getMessage()));

        }

        return self::SUCCESS;
    }
}