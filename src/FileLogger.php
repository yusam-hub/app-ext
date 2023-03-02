<?php

namespace YusamHub\AppExt;

use \Psr\Log\LogLevel;

class FileLogger extends Logger
{
    const FILE_SIZE_MB = 1024 * 1024;
    const FILE_ROTATOR_COUNT = 10;
    const FILE_NAME_DEFAULT = 'app';
    const LINE_FORMAT_NORMAL = 'normal';
    const LINE_FORMAT_JSON = 'json';

    protected string $lineFormat = "";
    protected string $logDir = "";
    protected string $name = "";
    protected int $fileMaxSize;
    protected int $fileRotatorCount;
    protected string $level;
    protected int $levelIndex;

    public function __construct(array $config = [])
    {
        $this->logDir = rtrim($config['logDir']??'', DIRECTORY_SEPARATOR);
        $this->name = $config['name']??self::FILE_NAME_DEFAULT;
        $this->fileMaxSize = $config['fileMaxSize']??self::FILE_SIZE_MB;
        $this->fileRotatorCount = $config['fileRotatorCount']??self::FILE_ROTATOR_COUNT;
        $this->lineFormat = $config['lineFormat']??self::LINE_FORMAT_NORMAL;
        $this->level = $config['level']??LogLevel::DEBUG;
        $this->levelIndex = array_search($this->level, self::LOG_LEVELS);
    }

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = [])
    {
        $ind = array_search($level, self::LOG_LEVELS);
        if ($ind >= $this->levelIndex) {
            $this->writeToFile($level, $message, $context);
        }
    }

    /**
     * @param int $rotatorId
     * @return string
     */
    private function createFileName(int $rotatorId): string
    {
        return $this->logDir . DIRECTORY_SEPARATOR . sprintf("%s-%s",$this->name, date("Y-m-d")) . ($rotatorId ? '.'.$rotatorId : '') . '.log';
    }

    /**
     * @param int $lineLen
     * @return string
     */
    private function getFileName(int $lineLen): string
    {
        $filename = $this->createFileName(0);

        if (file_exists($filename)) {

            $fileSize = filesize($filename);

            if ($fileSize !== false) {

                if ($fileSize + $lineLen >= $this->fileMaxSize) {

                    if (file_exists($this->createFileName($this->fileRotatorCount))) {
                        @unlink($this->createFileName($this->fileRotatorCount));
                    }

                    for($i = $this->fileRotatorCount-1; $i >= 1; $i--) {
                        if (file_exists($this->createFileName($i))) {
                            rename($this->createFileName($i), $this->createFileName($i + 1));
                        }
                    }

                    if (file_exists($this->createFileName(0))) {
                        rename($this->createFileName(0), $this->createFileName(1));
                    }
                }
            }
        }

        return $filename;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    private function writeToFile(string $level, string $message, array $context = []): void
    {
        if ($this->lineFormat === self::LINE_FORMAT_JSON) {
            $line =
                json_encode(
                    [
                        'dateTime' => date("Y-m-d H:i:s"),
                        'level' => $level,
                        'message' => $level,
                        'context' => $context,
                    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES). PHP_EOL;
        } else {
            $line =
                sprintf(
                    "[%s][%s] %s%s",
                    date("Y-m-d H:i:s"),
                    strtoupper($level),
                    $message,
                    empty($context) ? '' : ' ' . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                ). PHP_EOL;
        }
        file_put_contents($this->getFileName(strlen($line)), $line, FILE_APPEND);
    }

}