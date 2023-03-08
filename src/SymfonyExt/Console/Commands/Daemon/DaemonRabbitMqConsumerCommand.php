<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Daemon;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\RabbitMq\BaseRabbitMqConsumerMessage;
use YusamHub\AppExt\RabbitMq\RabbitMqConsumer;
use YusamHub\AppExt\RabbitMq\RabbitMqConsumerConfigModel;
use YusamHub\AppExt\ReactHttpServer\HttpServerConfigModel;
use YusamHub\AppExt\ReactHttpServer\ReactHttpServer;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;

class DaemonRabbitMqConsumerCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('daemon:rabbit-mq-consumer')
            ->setDescription('daemon:daemon:rabbit-mq-consumer:description')
            ->setHelp('daemon:daemon:rabbit-mq-consumer:help')
            ->addOption('worker-number', null,InputOption::VALUE_OPTIONAL, 'worker-number: any integer >= 0, default=0', 0)
            ->addOption('class-message', null, InputOption::VALUE_OPTIONAL, BaseRabbitMqConsumerMessage::class, BaseRabbitMqConsumerMessage::class)
        ;
    }

    /**
     * @param int $workerNumber
     * @return LoggerInterface
     */
    protected function getLoggerFromConfig(int $workerNumber): LoggerInterface
    {
        return app_ext_logger('rabbit-mq-consumer-' . $workerNumber);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $workerNumber = abs(intval($input->getOption('worker-number')));
        $classMessage = $input->getOption('class-message');

        $rabbitMqConsumer = new RabbitMqConsumer(
            new RabbitMqConsumerConfigModel(),
            new $classMessage()
        );

        $rabbitMqConsumer->setConsoleOutput($output);
        $rabbitMqConsumer->setConsoleOutputEnabled(true);
        $rabbitMqConsumer->setLogger($this->getLoggerFromConfig($workerNumber));

        $rabbitMqConsumer->daemon();

        return self::SUCCESS;
    }
}