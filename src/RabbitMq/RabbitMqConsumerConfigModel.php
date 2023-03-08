<?php

namespace YusamHub\AppExt\RabbitMq;
class RabbitMqConsumerConfigModel
{
    public int $prefetchSize = 0;
    public int $prefetchCount = 5;
    public string $exchangeName = 'default';
    public string $queueName = 'default';
    public string $routingKey = 'default';
    public string $consumerTag = 'default';

    public function __construct(array $config = [])
    {
        foreach($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}