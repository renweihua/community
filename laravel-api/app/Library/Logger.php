<?php

namespace App\Library;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

/**
 * Class LoggerFactory
 *
 * 封装 Log 类
 *
 * @package App
 */
class Logger
{
    protected static $log;

    const DEFAULT = 'community';

    // 默认使用 Channel 名为 app 来记录日志，您也可以通过使用 Log::get($name) 方法获得不同 Channel 的 Logger
    public static function get(string $name = self::DEFAULT)
    {
        self::$log = new MonologLogger($name);

        // 创建mysql文件夹
        $dir_path = dirname(dirname(__DIR__)) . '/storage/logs/' . self::$log->getName();
        if (!is_dir($dir_path)) mkdir($dir_path, 0755);

        $log_path  = storage_path('logs/' . self::$log->getName() . '/' . date('Y-m-d') . '.log');
        if (!file_exists($log_path)) {
            fopen($log_path, "w");
        }

        self::$log->pushHandler(new StreamHandler($log_path, MonologLogger::DEBUG));

        return self::$log;
    }

    public static function record(string $message, array $context = [], string $name = self::DEFAULT, int $level = MonologLogger::INFO)
    {
        if (!self::$log){
            self::$log = self::get($name);
        }
        return self::$log->addRecord($level, $message, $context);
    }
}
