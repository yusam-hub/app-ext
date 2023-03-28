<?php

namespace YusamHub\AppExt\RabbitMq;
class RabbitMqConsumerConfigModel extends RabbitMqPublisherConfigModel
{
    public int $prefetchSize = 0;
    public int $prefetchCount = 5;
    public string $consumerTag = 'default';
}