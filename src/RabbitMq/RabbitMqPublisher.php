<?php

namespace YusamHub\AppExt\RabbitMq;

use Bunny\Client;

class RabbitMqPublisher extends BaseRabbitMq
{
    protected RabbitMqPublisherConfigModel $rabbitMqPublisherConfigModel;

    /**
     * @param RabbitMqPublisherConfigModel $rabbitMqPublisherConfigModel
     * @param string|null $connectionName
     */
    public function __construct(
        RabbitMqPublisherConfigModel $rabbitMqPublisherConfigModel,
        ?string $connectionName = null)
    {
        $this->rabbitMqPublisherConfigModel = $rabbitMqPublisherConfigModel;
        parent::__construct($connectionName);
    }

    /**
     * @param string $message
     * @param array $headers
     * @return void
     */
    public function send(string $message, array $headers = []): void
    {
        $this->info(sprintf('Client [%s] started at [%s]', get_class($this), date(DATE_TIME_APP_EXT_FORMAT)));

        $this->info('Config: host: ' . $this->connectionConfig['host']);
        $this->info('Config: port: ' . $this->connectionConfig['port']);
        $this->info('Config: vhost: ' . $this->connectionConfig['vhost']);
        $this->info('Config: user: ' . $this->connectionConfig['user']);

        $this->debug('Trying to send message', [
            'message' => $message,
            'headers' => $headers,
            'to' => [
                'queueName' => $this->rabbitMqPublisherConfigModel->queueName,
                'exchangeName' => $this->rabbitMqPublisherConfigModel->exchangeName,
                'routingKey' => $this->rabbitMqPublisherConfigModel->routingKey,
            ]
        ]);
        try {
            $client = new Client($this->connectionConfig);

            $client->connect();

            if ($client->isConnected()) {

                $this->debug('Connect success');

                $channel = $client->channel();

                if ($channel->queueDeclare($this->rabbitMqPublisherConfigModel->queueName, false,true,false,false)) {

                    $this->debug('Queue declare success');

                    if ($channel->publish($message, $headers, $this->rabbitMqPublisherConfigModel->exchangeName, $this->rabbitMqPublisherConfigModel->routingKey)) {
                        $this->debug('Send message success');
                    } else {
                        $this->error('Send message fail');
                    }
                } else {
                    $this->error('Queue declare fail');
                }
            } else {
                $this->error('Connect fail');
            }
        } catch (\Throwable $e)
        {
            $this->error($e->getMessage(), app_ext_get_error_context($e));
        }
    }
}