<?php

namespace YusamHub\AppExt\RabbitMq;

use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Message;
use YusamHub\AppExt\RabbitMq\Interfaces\RabbitMqConsumerMessageInterface;

class BaseRabbitMqConsumerMessage implements RabbitMqConsumerMessageInterface
{
    public function onMessage(Message $message, Channel $channel, Client $client): bool
    {
        return true;
    }
}