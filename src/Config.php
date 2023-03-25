<?php

namespace YusamHub\AppExt;

use YusamHub\Helper\DotArray;

class Config
{

    protected static ?Config $instance = null;
    protected static ?string $configDir = null;

    /**
     * @param string|null $configDir
     * @return Config
     */
    public static function instance(?string $configDir = null): Config
    {
        if (is_null(self::$instance)) {

            self::$instance = new static($configDir);
        }

        return self::$instance ;
    }

    /**
     * @var DotArray[]
     */
    protected array $dotList = [];

    /**
     * @param string|null $configDir
     */
    public function __construct(?string $configDir = null)
    {
        if (!is_null($configDir)) {
            $this::$configDir = $configDir;
        }
    }

    /**
     * @param string $dotKey
     * @param $fileKey
     * @param $key
     * @return void
     */
    private function initDotList(string $dotKey, &$fileKey, &$key): void
    {
        $list = explode(".", $dotKey);
        $fileKey = array_shift($list);
        $key = implode(".", $list);
        if (empty($fileKey)) {
            throw new \RuntimeException(sprintf("FileKey [%s] not found", $fileKey));
        }
        if (!isset($this->dotList[$fileKey])) {
            $fullFilename = realpath(rtrim($this::$configDir, DIRECTORY_SEPARATOR)) . DIRECTORY_SEPARATOR . $fileKey . ".php";
            if (file_exists($fullFilename)) {
                $this->dotList[$fileKey] = helper_dot_array(include $fullFilename);
            } else {
                $this->dotList[$fileKey] = helper_dot_array([]);
            }
        }
    }

    /**
     * @param string $dotKey
     * @param mixed $default
     * @return mixed
     */
    public function get(string $dotKey, $default = null)
    {
        $this->initDotList($dotKey, $fileKey, $key);
        return $this->dotList[$fileKey]->get($key, $default);
    }

    /**
     * @param string $dotKey
     * @param $value
     * @return bool
     */
    public function set(string $dotKey, $value): bool
    {
        $this->initDotList($dotKey, $fileKey, $key);
        return $this->dotList[$fileKey]->set($key, $value);
    }

    /**
     * @param string $dotKey
     * @return bool
     */
    public function has(string $dotKey): bool
    {
        $this->initDotList($dotKey, $fileKey, $key);
        return $this->dotList[$fileKey]->has($key);
    }
}