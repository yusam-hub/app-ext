<?php

namespace YusamHub\AppExt\RabbitMq;

use Bunny\Async\Client;
use Bunny\Channel;
use Bunny\Message;
use Bunny\Protocol\MethodBasicConsumeOkFrame;
use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use YusamHub\AppExt\Interfaces\RabbitMqConsumerMessageInterface;

class RabbitMqConsumer extends BaseRabbitMq
{
    protected int $workerNumber;
    protected int $memoryUsageStart;
    protected int $memoryUsageRealStart;
    protected RabbitMqConsumerConfigModel $rabbitMqConsumerConfigModel;
    protected ?RabbitMqConsumerMessageInterface $rabbitMqConsumerMessage;
    protected LoopInterface $reactLoop;
    protected \Bunny\Async\Client $asyncClient;



    /**
     * @param RabbitMqConsumerConfigModel $rabbitMqConsumerConfigModel
     * @param RabbitMqConsumerMessageInterface|null $rabbitMqConsumerMessage
     * @param int $workerNumber
     * @param string|null $connectionName
     */
    public function __construct(
        RabbitMqConsumerConfigModel $rabbitMqConsumerConfigModel,
        ?RabbitMqConsumerMessageInterface $rabbitMqConsumerMessage = null,
        int $workerNumber = 0,
        ?string $connectionName = null)
    {
        $this->rabbitMqConsumerConfigModel = $rabbitMqConsumerConfigModel;
        $this->rabbitMqConsumerMessage = $rabbitMqConsumerMessage;
        $this->workerNumber = $workerNumber;
        $this->memoryUsageStart = memory_get_usage(false);
        $this->memoryUsageRealStart = memory_get_usage(true);
        parent::__construct($connectionName);
        $this->reactLoop = Loop::get();
        $this->asyncClient = new \Bunny\Async\Client($this->reactLoop, $this->connectionConfig);

    }

    /**
     * @return LoopInterface
     */
    public function getReactLoop(): LoopInterface
    {
        return $this->reactLoop;
    }

    /**
     * @return Client
     */
    public function getAsyncClient(): Client
    {
        return $this->asyncClient;
    }

    /**
     * @return void
     */
    public function daemon(): void
    {
        $this->info(sprintf('Daemon [%s] started at [%s]', get_class($this), date("Y-m-d H:i:s")));
        $this->info('--worker-number: ' . $this->workerNumber);
        $this->info('MemoryUsageStart: ' . $this->memoryUsageStart);
        $this->info('MemoryUsageRealStart: ' . $this->memoryUsageRealStart);
        $this->info('Config: host: ' . $this->connectionConfig['host']);
        $this->info('Config: port: ' . $this->connectionConfig['port']);
        $this->info('Config: vhost: ' . $this->connectionConfig['vhost']);
        $this->info('Config: user: ' . $this->connectionConfig['user']);

        $this
            ->asyncClient
            ->connect()
            ->then(
                function (Client $client)
                {
                    $this->debug('Connect success');
                    return $client->channel();
                },
                function($reason)
                {
                    $reasonMsg = "Unknown error";
                    if (is_string($reason)) {
                        $reasonMsg = $reason;
                    } else if ($reason instanceof \Throwable) {
                        $reasonMsg = $reason->getMessage();
                    }
                    $this->error('Connect fail', [
                        'reason' => $reasonMsg
                    ]);
                }
            )
            ->then(function (Channel $channel)
            {
                return $channel
                    ->qos($this->rabbitMqConsumerConfigModel->prefetchSize, $this->rabbitMqConsumerConfigModel->prefetchCount)
                    ->then(function () use ($channel) {
                        $this->debug('Qos success', [
                            'prefetchSize' => $this->rabbitMqConsumerConfigModel->prefetchSize,
                            'prefetchCount' => $this->rabbitMqConsumerConfigModel->prefetchCount
                        ]);
                        return $channel;
                    });
            })
            ->then(function (Channel $channel)
            {
                return $channel
                    ->exchangeDeclare(
                        $this->rabbitMqConsumerConfigModel->exchangeName,
                        'topic',
                        false,
                        true,
                        false,
                        false,
                        false,
                        []
                    )
                    ->then(function () use ($channel) {
                        $this->debug('Exchange declare success', [
                            'exchangeName' => $this->rabbitMqConsumerConfigModel->exchangeName,
                        ]);
                        return $channel;
                    });
            })
            ->then(function (Channel $channel) {
                return $channel
                    ->queueDeclare(
                        $this->rabbitMqConsumerConfigModel->queueName,
                        false,
                        true,
                        false,
                        false
                    )
                    ->then(function () use ($channel) {
                        $this->debug('Queue declare success', [
                            'queueName' => $this->rabbitMqConsumerConfigModel->queueName,
                        ]);
                        return $channel;
                    });
            })
            ->then(function (Channel $channel) {
                return $channel
                    ->queueBind(
                        $this->rabbitMqConsumerConfigModel->queueName,
                        $this->rabbitMqConsumerConfigModel->exchangeName,
                        $this->rabbitMqConsumerConfigModel->routingKey,
                        false,
                        []
                    )
                    ->then(function () use ($channel) {
                        $this->debug('Queue bind success', [
                            'routingKey' => $this->rabbitMqConsumerConfigModel->routingKey,
                        ]);
                        return $channel;
                    });
            })
            ->then(function (Channel $channel) use (&$channelRef) {

                $channelRef = $channel;

                $this->debug('Waiting for messages');

                $channel
                    ->consume(
                        \Closure::fromCallable([$this, 'consumeCallbackHandle']),
                        $this->rabbitMqConsumerConfigModel->queueName,
                        $this->rabbitMqConsumerConfigModel->consumerTag,
                    )
                    ->then(function (MethodBasicConsumeOkFrame $response) use (&$consumerTagRef) {
                        $consumerTagRef = $response->consumerTag;
                    })
                    ->done();
            })
            ->done();

        $stop_func = function (int $signal) use (&$channelRef, &$consumerTagRef) {
            $this->info(sprintf('Daemon received unix signal [%d]', $signal));
            $channelRef->cancel($consumerTagRef)->done(function() {
                $this->info(sprintf('Daemon [%s] finished at [%s]', get_class($this), date("Y-m-d H:i:s")));
                exit();
            });
        };

        $stop_func = function ($signal) use (&$channelRef, &$consumerTagRef, &$stop_func) {

            $this->reactLoop->removeSignal($signal, $stop_func);

            $this->info(sprintf('Daemon received unix signal [%d]', $signal));

            $channelRef
                ->cancel($consumerTagRef)
                ->done(
                    function() {
                        $this->info(sprintf('Daemon [%s] finished at [%s]', get_class($this), date("Y-m-d H:i:s")));
                        exit();
                    });
        };

        $this->reactLoop->addSignal(SIGTERM, $stop_func);

        $this->reactLoop->run();
    }

    /**
     * @param Message $message
     * @param Channel $channel
     * @param Client $client
     * @return void
     */
    protected function consumeCallbackHandle(Message $message, Channel $channel, Client $client)
    {
        $this->debug("Received message", [
            'consumerTag' => $message->consumerTag,
            'deliveryTag' => $message->deliveryTag,
            'content' => $message->content
        ]);

        try {

            if (!$this->onMessage($message, $channel, $client)) {
                $channel->reject($message, false);
            }

            $channel
                ->ack($message)
                ->then(
                    function() use ($message) {
                        $this->debug("ASK message success", [
                            'consumerTag' => $message->consumerTag,
                            'deliveryTag' => $message->deliveryTag,
                            'content' => $message->content
                        ]);
                        $this->debug(sprintf('MemoryUsage (now: %d, diff: %d, start: %d)', memory_get_usage(), memory_get_usage() - $this->memoryUsageStart, $this->memoryUsageStart));
                        $this->debug(sprintf('MemoryUsageReal (now: %d, diff: %d, start: %d)', memory_get_usage(true), memory_get_usage(true) - $this->memoryUsageRealStart, $this->memoryUsageRealStart));
                    },
                    function($reason) use ($message) {
                        $reasonMsg = "";
                        if (is_string($reason)) {
                            $reasonMsg = $reason;
                        } else if ($reason instanceof \Throwable) {
                            $reasonMsg = $reason->getMessage();
                        }
                        $this->error("ASK message fail", [
                            'consumerTag' => $message->consumerTag,
                            'deliveryTag' => $message->deliveryTag,
                            'reasonMsg' => $reasonMsg
                        ]);
                        $this->error(sprintf('MemoryUsage (now: %d, diff: %d, start: %d)', memory_get_usage(), memory_get_usage() - $this->memoryUsageStart, $this->memoryUsageStart));
                        $this->error(sprintf('MemoryUsageReal (now: %d, diff: %d, start: %d)', memory_get_usage(true), memory_get_usage(true) - $this->memoryUsageRealStart, $this->memoryUsageRealStart));

                    }
                )
                ->done();

        } catch (\Throwable $e) {
            $this->error($e->getMessage(), app_ext_get_error_context($e));
        }
    }

    /**
     * @param Message $message
     * @param Channel $channel
     * @param Client $client
     * @return bool
     */
    protected function onMessage(Message $message, Channel $channel, Client $client): bool
    {
        if (!is_null($this->rabbitMqConsumerMessage)) {
            return $this->rabbitMqConsumerMessage->onMessage($message, $channel, $client);
        }
        return true;
    }
}