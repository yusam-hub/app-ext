<?php

namespace YusamHub\AppExt\RabbitMq;

class RabbitMqPublisher extends BaseRabbitMq
{
    protected RabbitMqPublisherConfigModel $rabbitMqPublisherConfigModel;

    protected \Bunny\Client $client;
    protected ?\Bunny\Channel $channel = null;

    /**
     * @param RabbitMqPublisherConfigModel $rabbitMqPublisherConfigModel
     * @param string|null $connectionName
     * @throws \Exception
     */
    public function __construct(
        RabbitMqPublisherConfigModel $rabbitMqPublisherConfigModel,
        ?string $connectionName = null
    )
    {
        $this->rabbitMqPublisherConfigModel = $rabbitMqPublisherConfigModel;
        parent::__construct($connectionName);

        $this->info(sprintf('Client [%s] started at [%s]', get_class($this), date(DATE_TIME_APP_EXT_FORMAT)));

        $this->info('Config: host: ' . $this->connectionConfig['host']);
        $this->info('Config: port: ' . $this->connectionConfig['port']);
        $this->info('Config: vhost: ' . $this->connectionConfig['vhost']);
        $this->info('Config: user: ' . $this->connectionConfig['user']);

        $this->client = new \Bunny\Client($this->connectionConfig);

        $this->client->connect();

        if ($this->client->isConnected()) {
            $this->debug('Connect success');
            $this->channel = $this->client->channel();
        } else {
            $this->debug('Connect fail');
        }
    }

    public function __destruct()
    {
        if (!is_null($this->channel)) {
            $this->channel->close();
        }

        if ($this->client->isConnected()) {
            $this->client->disconnect();
        }
    }

    /**
     * @param string $message
     * @param array $headers
     * @return void
     */
    public function send(string $message, array $headers = []): void
    {
        $this->debug('Trying to send message', [
            'message' => $message,
            'headers' => $headers,
            'to' => [
                'exchangeName' => $this->rabbitMqPublisherConfigModel->exchangeName,
                'routingKey' => $this->rabbitMqPublisherConfigModel->routingKey,
            ]
        ]);

        if (!is_null($this->channel) && $this->channel->publish($message, $headers, $this->rabbitMqPublisherConfigModel->exchangeName, $this->rabbitMqPublisherConfigModel->routingKey)) {
            $this->debug('Send message success');
        } else {
            $this->error('Send message fail');
        }
    }
}