<?php

namespace App\Console\Commands;

use App\Models\Log\AdminLog;
use App\Models\Log\AdminLoginLog;
use App\Models\Log\UserLog;
use App\Models\Log\UserLoginLog;
use App\Models\Log\WebLog;
use App\Models\System\Notify;
use App\Models\User\UserSign;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class AutoTableBuild extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:autotablebuild';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '按月分表自动生成（Automatic generation of monthly tables）';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected $model_lists = [
        AdminLog::class, // 管理员操作日志表
        AdminLoginLog::class, // 管理员登录日志表
        WebLog::class, // WEB访问日志表
        UserLoginLog::class, // 会员登录日志表
        UserLog::class, // 会员操作日志表
        UserSign::class, // 会员签到记录表
        Notify::class, // 系统消息表
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach ($this->model_lists as $model){
            (new $model)->createMonthTable('', strtotime('+1 month'));
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
