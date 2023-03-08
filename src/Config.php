<?php

namespace YusamHub\AppExt;

class Config
{
    public static string $CONFIG_DIR = "";
    protected static ?Config $instance = null;

    /**
     * @return Config
     */
    public static function instance(): Config
    {
        if (is_null(self::$instance)) {

            self::$instance = new static();
        }

        return self::$instance ;
    }

    /**
     * @var DotArray[]
     */
    protected array $dotList = [];

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
            $fullFilename = realpath(rtrim($this::$CONFIG_DIR, DIRECTORY_SEPARATOR)) . DIRECTORY_SEPARATOR . $fileKey . ".php";
            if (file_exists($fullFilename)) {
                $this->dotList[$fileKey] = app_ext_dot_array(include $fullFilename);
            } else {
                $this->dotList[$fileKey] = app_ext_dot_array([]);
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

}