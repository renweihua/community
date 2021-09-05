<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // var_dump('schedule：' . date('Y-m-d H:i:s'));

        // 每月1号调用：按月分表自动生成
        $schedule->command('autotablebuild')->monthlyOn();

        // 同步抖音作者的视频
        $schedule->command('sync_douyin_videos')->wednesdays();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        /**
         * 自动加载多模块的自定义命令行
         */
        $modules_path = config('modules.paths.modules');
        if ($dirs = get_dir_files($modules_path)){
            foreach ($dirs as $dir){
                if (is_dir($console_path = $modules_path . '/' . $dir . '/Console'))
                    $this->load($console_path = $modules_path . '/' . $dir . '/Console');
            }
        }

        require base_path('routes/console.php');
    }
}
