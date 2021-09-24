<?php

namespace App\Utils;

class ExecutionTime
{
    static $start_time = '';

    public static function setStartTime($time)
    {
        self::$start_time = $time;
    }
}