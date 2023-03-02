<?php

namespace YusamHub\AppExt;

class Console
{
    public static string $LOG_DIR = "";
    protected static ?Console $instance = null;

    /**
     * @return Console
     */
    public static function instance(): Console
    {
        if (is_null(self::$instance)) {

            self::$instance = new static();
        }

        return self::$instance ;
    }
}