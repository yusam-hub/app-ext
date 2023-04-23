<?php

namespace YusamHub\AppExt\RabbitMq;
use YusamHub\AppExt\Models\ConfigModel;

class RabbitMqPublisherConfigModel extends ConfigModel
{
    protected static string $dotKeyAsConfigItemDefault = 'rabbit-mq.destinationDefault';
    protected static string $dotKeyAsConfigItems = 'rabbit-mq.destinations.%s';
    public string $exchangeName = 'default';
    public string $routingKey = 'default';
}