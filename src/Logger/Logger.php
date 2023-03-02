<?php

namespace YusamHub\AppExt\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

abstract class Logger extends AbstractLogger
{
    const LOG_LEVELS = [
        LogLevel::DEBUG,
        LogLevel::INFO,
        LogLevel::NOTICE,
        LogLevel::WARNING,
        LogLevel::ERROR,
        LogLevel::CRITICAL,
        LogLevel::ALERT,
        LogLevel::EMERGENCY,
    ];

    public array $records = [];

    public array $recordsByLevel = [];

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = [])
    {
        $record = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];

        $this->recordsByLevel[$record['level']][] = $record;
        $this->records[] = $record;
    }

    /**
     * @param $level
     * @return bool
     */
    public function hasRecords($level): bool
    {
        return isset($this->recordsByLevel[$level]);
    }

    /**
     * @param $record
     * @param $level
     * @return bool
     */
    public function hasRecord($record, $level): bool
    {
        if (is_string($record)) {
            $record = ['message' => $record];
        }
        return $this->hasRecordThatPasses(function ($rec) use ($record) {
            if ($rec['message'] !== $record['message']) {
                return false;
            }
            if (isset($record['context']) && $rec['context'] !== $record['context']) {
                return false;
            }
            return true;
        }, $level);
    }

    /**
     * @param $message
     * @param $level
     * @return bool
     */
    public function hasRecordThatContains($message, $level): bool
    {
        return $this->hasRecordThatPasses(function ($rec) use ($message) {
            return strpos($rec['message'], $message) !== false;
        }, $level);
    }

    /**
     * @param $regex
     * @param $level
     * @return bool
     */
    public function hasRecordThatMatches($regex, $level): bool
    {
        return $this->hasRecordThatPasses(function ($rec) use ($regex) {
            return preg_match($regex, $rec['message']) > 0;
        }, $level);
    }

    /**
     * @param callable $predicate
     * @param $level
     * @return bool
     */
    public function hasRecordThatPasses(callable $predicate, $level): bool
    {
        if (!isset($this->recordsByLevel[$level])) {
            return false;
        }
        foreach ($this->recordsByLevel[$level] as $i => $rec) {
            if (call_user_func($predicate, $rec, $i)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (preg_match('/(.*)(Debug|Info|Notice|Warning|Error|Critical|Alert|Emergency)(.*)/', $method, $matches) > 0) {
            $genericMethod = $matches[1] . ('Records' !== $matches[3] ? 'Record' : '') . $matches[3];
            $level = strtolower($matches[2]);
            if (method_exists($this, $genericMethod)) {
                $args[] = $level;
                return call_user_func_array([$this, $genericMethod], $args);
            }
        }
        throw new \BadMethodCallException('Call to undefined method ' . get_class($this) . '::' . $method . '()');
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->records = [];
        $this->recordsByLevel = [];
    }

}