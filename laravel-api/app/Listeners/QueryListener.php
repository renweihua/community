<?php

namespace App\Listeners;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class QueryListener
{
    protected $log;

    /**
     * QueryListener constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->log = new Logger('mysql');

        // 创建mysql文件夹
        $dir_path = dirname(dirname(__DIR__)) . '/storage/logs/' . $this->log->getName();
        if (!is_dir($dir_path)) mkdir($dir_path, 0755);

        $log_path  = storage_path('logs/' . $this->log->getName() . '/' . date('Y-m-d') . '.log');
        if (!file_exists($log_path)) {
            fopen($log_path, "w");
        }

        $this->log->pushHandler(new StreamHandler($log_path, Logger::DEBUG));
    }

    /**
     * Handle the event.
     *
     * @param object $event
     *
     * @return void
     */
    public function handle($event): void
    {
        if (env('APP_DEBUG') == true) {
            /**
             * sql语句的监听
             */
            $sql = str_replace("?", "'%s'", $event->sql);
            $log = vsprintf($sql, $event->bindings);

            $this->log->addRecord(Logger::DEBUG, '[' . $event->time . '] | ' . $log . ' |');
        }
    }
}
