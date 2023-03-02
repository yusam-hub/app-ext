<?php

namespace YusamHub\AppExt;

class Log
{
    public static string $LOG_DIR = "";
    protected static ?Log $instance = null;

    /**
     * @return Log
     */
    public static function instance(): Log
    {
        if (is_null(self::$instance)) {

            self::$instance = new static();
        }

        return self::$instance ;
    }
}