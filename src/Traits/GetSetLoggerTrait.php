<?php

namespace YusamHub\AppExt\Traits;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\Output;

trait GetSetLoggerTrait
{
    private ?LoggerInterface $logger = null;

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    public function setLogger(?LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    private function doConsoleOutput($level, $message, array $context = array(), bool $consoleOutputEnabled = false): void
    {
        if ($consoleOutputEnabled && isset($this->consoleOutput) &&  $this->consoleOutput instanceof Output) {
            $this->consoleOutput->writeln(
                sprintf(
                    "[%s][%s] %s%s",
                    date("Y-m-d H:i:s"),
                    strtoupper($level),
                    $message,
                    (!empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '')
                )
            );
        }
    }

    public function emergency($message, array $context = array(), bool $consoleOutputEnabled = false)
    {
        $this->logger->emergency($message, $context);
        $this->doConsoleOutput(LogLevel::EMERGENCY, $message, $context, $consoleOutputEnabled);
    }

    public function alert($message, array $context = array(), bool $consoleOutputEnabled = false)
    {
        $this->logger->alert($message, $context);
        $this->doConsoleOutput(LogLevel::ALERT, $message, $context, $consoleOutputEnabled);
    }

    public function critical($message, array $context = array(), bool $consoleOutputEnabled = false)
    {
        $this->logger->critical($message, $context);
        $this->doConsoleOutput(LogLevel::CRITICAL, $message, $context, $consoleOutputEnabled);
    }

    public function error($message, array $context = array(), bool $consoleOutputEnabled = false)
    {
        $this->logger->error($message, $context);
        $this->doConsoleOutput(LogLevel::ERROR, $message, $context, $consoleOutputEnabled);
    }

    public function warning($message, array $context = array(), bool $consoleOutputEnabled = false)
    {
        $this->logger->warning($message, $context);
        $this->doConsoleOutput(LogLevel::WARNING, $message, $context, $consoleOutputEnabled);
    }

    public function notice($message, array $context = array(), bool $consoleOutputEnabled = false)
    {
        $this->logger->notice($message, $context);
        $this->doConsoleOutput(LogLevel::NOTICE, $message, $context, $consoleOutputEnabled);
    }

    public function info($message, array $context = array(), bool $consoleOutputEnabled = false)
    {
        $this->logger->info($message, $context);
        $this->doConsoleOutput(LogLevel::INFO, $message, $context, $consoleOutputEnabled);
    }

    public function debug($message, array $context = array(), bool $consoleOutputEnabled = false)
    {
        $this->logger->debug($message, $context);
        $this->doConsoleOutput(LogLevel::DEBUG, $message, $context, $consoleOutputEnabled);
    }

    public function log($level, $message, array $context = array(), bool $consoleOutputEnabled = false)
    {
        $this->logger->log($level, $message, $context);
        $this->doConsoleOutput($level, $message, $context, $consoleOutputEnabled);
    }
}