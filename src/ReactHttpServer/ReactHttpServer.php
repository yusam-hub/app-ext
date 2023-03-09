<?php

namespace YusamHub\AppExt\ReactHttpServer;

use YusamHub\AppExt\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Interfaces\GetSetLoggerInterface;
use YusamHub\AppExt\ReactHttpServer\Middleware\RequestBodyParserMiddleware;
use YusamHub\AppExt\ReactHttpServer\Middleware\RoutesMiddleware;
use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;

class ReactHttpServer implements GetSetConsoleInterface, GetSetLoggerInterface
{
    use GetSetConsoleTrait;
    use GetSetLoggerTrait;
    public const SUCCESS = 0;
    public const FAILURE = 1;
    protected HttpServerConfigModel $httpServerConfig;
    protected int $workerNumber;
    protected string $routesConfigFile;
    protected int $memoryUsageStart;
    protected int $memoryUsageRealStart;

    protected int $counterPromises = 0;
    protected int $counterRequests = 0;

    public function __construct(HttpServerConfigModel $httpServerConfig, string $routesConfigFile, int $workerNumber = 0)
    {
        $this->httpServerConfig = $httpServerConfig;
        $this->workerNumber = $workerNumber;
        $this->routesConfigFile = $routesConfigFile;
        $this->memoryUsageStart = memory_get_usage(false);
        $this->memoryUsageRealStart = memory_get_usage(true);
    }

    /**
     * @return HttpServerConfigModel
     */
    public function getHttpServerConfig(): HttpServerConfigModel
    {
        return $this->httpServerConfig;
    }

    /**
     * @return int
     */
    public function getWorkerNumber(): int
    {
        return $this->workerNumber;
    }

    /**
     * @return int
     */
    public function getMemoryUsageStart(): int
    {
        return $this->memoryUsageStart;
    }

    /**
     * @return int
     */
    public function getMemoryUsageRealStart(): int
    {
        return $this->memoryUsageRealStart;
    }

    /**
     * @return int
     */
    public function incCounterPromises(): int
    {
        $this->counterPromises++;
        return $this->counterPromises;
    }

    /**
     * @return int
     */
    public function getCounterPromises(): int
    {
        return $this->counterPromises;
    }

    /**
     * @return int
     */
    public function incCounterRequests(): int
    {
        $this->counterRequests++;
        return $this->counterRequests;
    }

    /**
     * @return int
     */
    public function getCounterRequests(): int
    {
        return $this->counterRequests;
    }

    /**
     * @return string
     */
    public function getRoutesConfigFile(): string
    {
        return $this->routesConfigFile;
    }

    /**
     * @return int
     */
    public function run(): int
    {
        $this->info(sprintf('Server [%s] started at [%s]', get_class($this), date("Y-m-d H:i:s")));
        $this->info('--socket-mode: ' . $this->httpServerConfig->socketServerMode);
        $this->info('--worker-number: ' . $this->workerNumber);
        $this->info('MemoryUsageStart: ' . $this->memoryUsageStart);
        $this->info('MemoryUsageRealStart: ' . $this->memoryUsageRealStart);

        $loop = \React\EventLoop\Loop::get();

        $http = new \React\Http\HttpServer(
            new \React\Http\Middleware\StreamingRequestMiddleware(),
            new \React\Http\Middleware\LimitConcurrentRequestsMiddleware($this->httpServerConfig->limitConcurrentRequests),
            new \React\Http\Middleware\RequestBodyBufferMiddleware($this->httpServerConfig->limitRequestBodyBuffer),
            new RequestBodyParserMiddleware(),
            new RoutesMiddleware($this)
        );

        $http->on('error', function (\Throwable $e) {
            $this->error('Server: ' . get_class($this), app_ext_get_error_context($e, true));
        });

        if ($this->httpServerConfig->socketServerMode === $this->httpServerConfig::SOCKET_SERVER_MODE_IP) {
            $uri = sprintf($this->httpServerConfig->socketServerIpUri,  $this->workerNumber);
            $socket = new \React\Socket\SocketServer($uri, [], $loop);
        } else {
            $dir = pathinfo($this->httpServerConfig->socketServerPathUri, PATHINFO_DIRNAME);
            $this->info('Checking dir: ' . $dir);
            if (!file_exists($dir)) {
                $this->info('Creating dir: ' . $dir);
                $f = mkdir(pathinfo($this->httpServerConfig->socketServerPathUri, PATHINFO_DIRNAME), 0777, true);
                if ($f) {
                    $this->info('Success dir: ' . $dir);
                } else {
                    $this->error(sprintf('Dir [%s] not created', $dir));
                    return self::FAILURE;
                }
            } else {
                $this->info('Success dir: ' . $dir);
            }

            $workerFile = sprintf($this->httpServerConfig->socketServerPathUri,  $this->workerNumber);
            if (file_exists($workerFile)) {
                unlink($workerFile);
            }

            $uri = 'unix://' . $workerFile;
            $socket = new \React\Socket\SocketServer($uri, [], $loop);

            if (file_exists($workerFile) && is_readable($workerFile)) {
                if (chmod($workerFile, 0777) === false) {
                    $this->error(sprintf('Failed to change permission for socket file [%s]', $workerFile));
                    return self::FAILURE;
                }
            } else {
                $this->error(sprintf('Unix socket file [%s] not found', $workerFile));
                return self::FAILURE;
            }
        }
        $this->info('LISTEN: ' . $uri);

        $stop_func = function ($signal) use ($loop, $socket, &$stop_func) {
            $loop->removeSignal($signal, $stop_func);
            $this->info(sprintf('Unix signal [%d]', $signal));
            $socket->close();
            $this->info(sprintf('Server [%s] finished at [%s]', get_class($this), date("Y-m-d H:i:s")));
            if ($this->httpServerConfig->socketServerMode === $this->httpServerConfig::SOCKET_SERVER_MODE_UNIX_FILE) {
                unlink(sprintf($this->httpServerConfig->socketServerPathUri,  $this->workerNumber));
            }
        };

        $loop->addSignal(SIGTERM, $stop_func);

        $http->listen($socket);

        return self::SUCCESS;
    }
}