<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Daemon;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;

class DaemonWebSocketServerCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('daemon:web-socket-server')
            ->setDescription('daemon:web-socket-server:description')
            ->setHelp('daemon:daemon:web-socket-server:help')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $server = app_ext_config('web-socket.serverDefault');
        $config = app_ext_config('web-socket.servers.' . $server);
        \YusamHub\WebSocket\WebSocketFactory::setWebSocketServerClass($config['class']??'');
        $webSocketDaemon = \YusamHub\WebSocket\WebSocketFactory::newDaemon(
            \YusamHub\WebSocket\WebSocketFactory::newConfig($config['connection']),
            \YusamHub\WebSocket\WebSocketFactory::newOutput()
        );

        $webSocketDaemon->setDebugging(true);

        $webSocketDaemon->run(
            $config['incomingMessagesClass'],
            $config['externalMessagesClass']
        );

        return self::SUCCESS;
    }
}