<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Smarty;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;
class SmartyCheckCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('smarty:check')
            ->setDescription('smarty:check:description')
            ->setHelp('smarty:check:help')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("");

        $output->writeln($this->tagGreen(sprintf('TEMPLATE SCHEME DEFAULT: %s', app_ext_smarty_global()->getDefaultTemplateScheme())));
        $out = [];

        foreach(app_ext_smarty_global()->getTemplateSchemeNames() as $templateScheme)
        {
            $dir = app_ext_smarty_global()->template($templateScheme)->getTemplateDir();
            try {

                $out[] = [
                    $templateScheme, $dir, $this->tagGreen(app_ext_smarty_global()->template($templateScheme)->view('check',['check' => time()]))
                ];

            } catch (\Throwable $e) {

                $out[] = [
                    $templateScheme, $dir, $this->tagRed('ERROR: ' . $e->getMessage())
                ];

            }
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Scheme', 'Directory', 'Check'])
            ->setRows($out)
        ;
        $table->render();

        return self::SUCCESS;
    }
}