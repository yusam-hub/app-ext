<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Client;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\RabbitMq\RabbitMqPublisher;
use YusamHub\AppExt\RabbitMq\RabbitMqPublisherConfigModel;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;

class ClientWebSocketExternalCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('client:web-socket-external')
            ->setDescription('client:web-socket-external:description')
            ->setHelp('client:web-socket-external:help')
            ->addArgument('message', InputArgument::REQUIRED, '')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $message = $input->getArgument('message');
        $server = app_ext_config('web-socket.externalDefault');
        $config = app_ext_config('web-socket.externals.' . $server);

        $webSocketClient = new \YusamHub\WebSocket\WebSocketClient(
            \YusamHub\WebSocket\WebSocketFactory::newConfig(
                $config['connection']
            ),
            \YusamHub\WebSocket\WebSocketFactory::newOutput()
        );

        try {

            $webSocketClient->externalSendStringMessage($message);

        } catch (\Throwable $e) {
            echo $e->getMessage(), PHP_EOL;
        }

        return self::SUCCESS;
    }
}