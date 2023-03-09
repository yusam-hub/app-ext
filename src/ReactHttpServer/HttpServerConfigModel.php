<?php

namespace YusamHub\AppExt\ReactHttpServer;
class HttpServerConfigModel
{
    const SOCKET_SERVER_MODE_IP = 1;
    const SOCKET_SERVER_MODE_UNIX_FILE = 2;
    public bool $isDebugging = true;
    public int $limitConcurrentRequests = 100;
    public int $limitRequestBodyBuffer = 2097152;
    public int $socketServerMode = self::SOCKET_SERVER_MODE_IP;
    public string $socketServerPathUri = '/tmp/react-http-server-socks/server.worker%d.sock';
    public string $tmpFileDir = '/tmp/react-http-server-files';
    public string $socketServerIpUri = '0.0.0.0:1808%d';

    public function __construct(array $config = [])
    {
        foreach($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}