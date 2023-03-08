<?php

namespace YusamHub\AppExt\RabbitMq;
class RabbitMqPublisherConfigModel
{
    public string $queueName = 'default';
    public string $exchangeName = 'default';
    public string $routingKey = 'default';

    public function __construct(array $config = [])
    {
        foreach($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}