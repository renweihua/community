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

        // 年月的日志目录
        $year_path = $dir_path . '/' . date('Y');
        if (!is_dir($year_path)) mkdir($year_path, 0755);

        $month_path = $year_path . '/' . date('m');
        if (!is_dir($month_path)) mkdir($month_path, 0755);

        $log_path  = storage_path('logs/' . $this->log->getName() . '/' . date('Y') . '/' . date('m') . '/' . date('d') . '.log');
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
            if ($event->bindings){
                $log = vsprintf($sql, $event->bindings);
            }else{
                $log = $sql;
            }

            $this->log->addRecord(Logger::DEBUG, '[' . $event->time . '] | ' . $log . ' |');
        }
    }
}
