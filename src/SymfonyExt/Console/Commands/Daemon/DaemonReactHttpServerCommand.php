<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Daemon;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\ReactHttpServer\HttpServerConfigModel;
use YusamHub\AppExt\ReactHttpServer\ReactHttpServer;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;

class DaemonReactHttpServerCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('daemon:react-http-server')
            ->setDescription('daemon:react-http-server:description')
            ->setHelp('daemon:react-http-server:help')
            ->addOption('socket-mode', null, InputOption::VALUE_OPTIONAL, 'socket mode: 1 = ip, 2 = unix-file; default=0=config', 0)
            ->addOption('worker-number', null,InputOption::VALUE_OPTIONAL, 'worker-number: any integer >= 0, default=0', 0)
        ;
    }

    /**
     * @param int $workerNumber
     * @return LoggerInterface
     */
    protected function getLoggerFromConfig(int $workerNumber): LoggerInterface
    {
        return app_ext_logger('react-http-server-' . $workerNumber);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $socketMode = intval($input->getOption('socket-mode'));
        $workerNumber = abs(intval($input->getOption('worker-number')));

        $httpServerConfigModel = new HttpServerConfigModel(app_ext_config('react-http-server.httpServerConfigModel'));

        if ($socketMode === HttpServerConfigModel::SOCKET_SERVER_MODE_IP) {
            $httpServerConfigModel->socketServerMode = $socketMode;
        }

        if ($socketMode === HttpServerConfigModel::SOCKET_SERVER_MODE_UNIX_FILE) {
            $httpServerConfigModel->socketServerMode = $socketMode;
        }
        $httpServer = new ReactHttpServer(
            $httpServerConfigModel,
            rtrim(app_ext_config('routes.path'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . app_ext_config('routes.default'),
            $workerNumber
        );

        $httpServer->setConsoleOutput($output);
        $httpServer->setLoggerConsoleOutputEnabled(true);
        $httpServer->setLogger($this->getLoggerFromConfig($workerNumber));

        return $httpServer->run();
    }
}