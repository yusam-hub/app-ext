<?php

namespace YusamHub\AppExt\Traits;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\Output;
use YusamHub\AppExt\Traits\Interfaces\GetSetConsoleInterface;

trait GetSetLoggerTrait
{
    private ?LoggerInterface $logger = null;
    private bool $consoleOutputEnabled = false;

    public function hasLogger(): bool
    {
        return !is_null($this->logger);
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    public function setLogger(?LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function getLoggerConsoleOutputEnabled(): bool
    {
        return $this->consoleOutputEnabled;
    }

    public function setLoggerConsoleOutputEnabled(bool $value): void
    {
        $this->consoleOutputEnabled = $value;
    }

    private function doConsoleOutput($level, $message, array $context = array()): void
    {
        if ($this->getLoggerConsoleOutputEnabled()) {
            $this->getConsoleOutput()->writeln(
                sprintf(
                    "[%s][%s] %s%s",
                    date(DATE_TIME_APP_EXT_FORMAT),
                    strtoupper($level),
                    $message,
                    (!empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '')
                )
            );
        }
    }

    public function emergency($message, array $context = array())
    {
        if ($this->hasLogger()) {
            $this->getLogger()->emergency($message, $context);
        }
        $this->doConsoleOutput(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        if ($this->hasLogger()) {
            $this->getLogger()->alert($message, $context);
        }
        $this->doConsoleOutput(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = array())
    {
        if ($this->hasLogger()) {
            $this->getLogger()->critical($message, $context);
        }
        $this->doConsoleOutput(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = array())
    {
        if ($this->hasLogger()) {
            $this->getLogger()->error($message, $context);
        }
        $this->doConsoleOutput(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        if ($this->hasLogger()) {
            $this->getLogger()->warning($message, $context);
        }
        $this->doConsoleOutput(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        if ($this->hasLogger()) {
            $this->getLogger()->notice($message, $context);
        }
        $this->doConsoleOutput(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = array())
    {
        if ($this->hasLogger()) {
            $this->getLogger()->info($message, $context);
        }
        $this->doConsoleOutput(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        if ($this->hasLogger()) {
            $this->getLogger()->debug($message, $context);
        }
        $this->doConsoleOutput(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        if ($this->hasLogger()) {
            $this->getLogger()->log($level, $message, $context);
        }
        $this->doConsoleOutput($level, $message, $context);
    }
}