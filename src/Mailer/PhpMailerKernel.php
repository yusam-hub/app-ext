<?php

namespace YusamHub\AppExt\Mailer;

use PHPMailer\PHPMailer\Exception;
use YusamHub\AppExt\Traits\GetSetConsoleTrait;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetConsoleInterface;
use YusamHub\AppExt\Traits\Interfaces\GetSetLoggerInterface;

class PhpMailerKernel implements GetSetLoggerInterface, GetSetConsoleInterface
{
    use GetSetLoggerTrait;
    use GetSetConsoleTrait;

    protected static ?PhpMailerKernel $instance = null;
    /**
     * @var array|PHPMailerExt[]
     */
    protected array $connections = [];

    /**
     * @return PhpMailerKernel
     */
    public static function global(): PhpMailerKernel
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
            self::$instance->setLogger(app_ext_logger());
        }
        return self::$instance;
    }

    /**
     * @param string|null $connectionName
     * @return PHPMailerExt
     * @throws Exception
     */
    public function connection(?string $connectionName = null): PHPMailerExt
    {
        if (is_null($connectionName)) {
            $connectionName = $this->getDefaultConnectionName();
        }

        if (isset($this->connections[$connectionName])) {
            return $this->connections[$connectionName];
        }

        $phpMailerExt = new PHPMailerExt(app_ext_config('php-mailer.connections.'.$connectionName));
        return $this->connections[$connectionName] = $phpMailerExt;
    }

    /**
     * @return string
     */
    public function getDefaultConnectionName(): string
    {
        return (string) app_ext_config('php-mailer.connectionDefault');
    }

    /**
     * @return array
     */
    public function getConnectionNames(): array
    {
        return array_keys((array) app_ext_config('php-mailer.connections'));
    }

    /**
     * @param string|null $connectionName
     * @return void
     */
    public function connectionClose(?string $connectionName = null): void
    {
        if (is_null($connectionName)) {
            $connectionName = $this->getDefaultConnectionName();
        }

        if (isset($this->connections[$connectionName])) {
            unset($this->connections[$connectionName]);
        }
    }

    /**
     * @return array|PHPMailerExt[]
     */
    public function getConnections(): array
    {
        return $this->connections;
    }
}