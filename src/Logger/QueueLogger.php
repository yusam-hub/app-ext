<?php

namespace YusamHub\AppExt\Logger;

class QueueLogger extends Logger
{
    public function __construct(array $config = [])
    {

    }

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = [])
    {
        //todo: посылаем в очередь редис и уже из очереди записываем в файл, тогда файл будет не конкурировать на запись
    }

}