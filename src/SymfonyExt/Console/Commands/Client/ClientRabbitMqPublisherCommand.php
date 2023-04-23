<?php

namespace YusamHub\AppExt\SymfonyExt\Console\Commands\Client;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\RabbitMq\RabbitMqPublisher;
use YusamHub\AppExt\RabbitMq\RabbitMqPublisherConfigModel;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;

class ClientRabbitMqPublisherCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('client:rabbit-mq-publisher')
            ->setDescription('client:daemon:rabbit-mq-publisher:description')
            ->setHelp('client:daemon:rabbit-mq-publisher:help')
            ->addArgument('message', InputArgument::REQUIRED)
            ->addOption('exchangeName', null,InputOption::VALUE_OPTIONAL, '', 'default')
            ->addOption('routingKey', null,InputOption::VALUE_OPTIONAL, '', 'default')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rabbitMqPublisher = new RabbitMqPublisher(
            new RabbitMqPublisherConfigModel([
                'exchangeName' => $input->getOption('exchangeName'),
                'routingKey' => $input->getOption('routingKey'),
            ]),
        );

        $rabbitMqPublisher->setConsoleOutput($output);
        $rabbitMqPublisher->setLoggerConsoleOutputEnabled(true);

        $rabbitMqPublisher->send($input->getArgument('message'));

        $rabbitMqPublisher = null;

        return self::SUCCESS;
    }
}