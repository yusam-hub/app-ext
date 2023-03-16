<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Client;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\RabbitMq\RabbitMqPublisher;
use YusamHub\AppExt\RabbitMq\RabbitMqPublisherConfigModel;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;

class ClientWebSocketInternalCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('client:web-socket-internal')
            ->setDescription('client:web-socket-internal:description')
            ->setHelp('client:web-socket-internal:help')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $server = app_ext_config('web-socket.clientDefault');
        $config = app_ext_config('web-socket.clients.' . $server);

        $webSocketClient = new \YusamHub\WebSocket\WebSocketClient(
            \YusamHub\WebSocket\WebSocketFactory::newConfig(
                $config['connection']
            ),
            \YusamHub\WebSocket\WebSocketFactory::newOutput()
        );

        try {

            $webSocketClient->daemon([
                \YusamHub\WebSocket\WsClient\OutgoingMessages\PingOutgoingMessage::class,
            ],[
                \YusamHub\WebSocket\WsClient\IncomingMessages\PongIncomingMessage::class,
            ]);

        } catch (\Throwable $e) {
            echo $e->getMessage(), PHP_EOL;
        }

        return self::SUCCESS;
    }
}