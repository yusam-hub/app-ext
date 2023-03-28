<?php

namespace YusamHub\AppExt\RabbitMq;
class RabbitMqConsumerConfigModel extends RabbitMqPublisherConfigModel
{
    const EXCHANGE_TYPE_TOPIC = 'topic';
    const EXCHANGE_TYPE_X_DELAYED_MESSAGE = 'x-delayed-message';
    public int $prefetchSize = 0;
    public int $prefetchCount = 5;
    public string $consumerTag = 'default';
    public string $exchangeType = self::EXCHANGE_TYPE_TOPIC;
    //public string $exchangeType = self::EXCHANGE_TYPE_X_DELAYED_MESSAGE;
    public array $exchangeArgs = [
        //'x-delayed-type' => self::EXCHANGE_TYPE_TOPIC,
    ];
    public array $queueBindArgs = [];
}