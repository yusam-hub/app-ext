<?php

namespace YusamHub\AppExt\RabbitMq\Interfaces;

use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Message;
interface RabbitMqConsumerMessageInterface
{
    public function onMessage(Message $message, Channel $channel, Client $client): bool;
}