<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Table;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\Db\Model\ApiUserModel\ApiUserModel;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;

class TableApiUserAddCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('table:api-user-add')
            ->setDescription('table:api-user-add:description')
            ->setHelp('table:api-user-add:help')
            ->addArgument('description', InputArgument::REQUIRED, 'Описание, заметка для ключа')
            ->addOption('isBlocked', null, InputOption::VALUE_OPTIONAL, 'Признак блокировки записи: 0|1', 0)
        ;
    }

    /**
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("");

        try {
            $apiUserModel = new ApiUserModel();
            $apiUserModel->setPdoExtKernel(app_ext_db_global());
            $apiUserModel->apiToken = md5(microtime() . random_int(100000, 999999));
            $apiUserModel->apiSign = md5($apiUserModel->apiToken . microtime() . random_int(100000, 999999));
            $apiUserModel->description = $input->getArgument('description');
            if ((int)$input->getOption('isBlocked') === 1) {
                $apiUserModel->blockedAt = date(DATE_TIME_APP_EXT_FORMAT);
                $apiUserModel->blockedDescription = 'Created';
            }
            if ($apiUserModel->save()) {
                $out = [];

                $fields = $apiUserModel->getAttributes();
                foreach ($fields as $k => $v) {
                    $out[] = [$k, $v];
                }

                $table = new Table($output);
                $table
                    ->setHeaders(['Field', 'Value'])
                    ->setRows($out)
                ;
                $table->render();
            }
        } catch (\Throwable $e) {
            $output->writeln($this->tagRed($e->getMessage()));
        }

        return self::SUCCESS;
    }
}