<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncDatabaseTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:database:tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步导入数据表';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $database = getenv('DB_DATABASE');
        if (!DB::select("SHOW DATABASES LIKE '{$database}'")){
            return $this->error('请先创建数据库`' . $database . '`');
        }

        // $this->info();

        $sync = true;
        $exist = DB::select("SHOW TABLES LIKE '" . getenv('DB_PREFIX') . "admins'");
        if ($exist){
            $sync = false;
            if ($this->confirm('已存在数据表，请选择是否强制覆盖数据库?')) {
                $sync = true;
            }
        }

        if ($sync){
            $bar = $this->output->createProgressBar(2);

            $bar->setBarCharacter('<comment>=</comment>');
            $bar->setEmptyBarCharacter(' ');
            $bar->setProgressCharacter('|');
            $bar->setBarWidth(50);
            $bar->advance();

            $result = DB::unprepared(file_get_contents(base_path('../cnpscy-community.sql')));

            $bar->advance();
            $bar->finish();
            echo PHP_EOL;


            if (!$result){
                $this->error('数据表导入失败！');
            }
            $this->info('数据表导入成功！');
        }

        // 执行：自动按月分表
        $this->autotablebuild();

        return 0;
    }

    private function autotablebuild()
    {
        // 执行自动分月，避免新安装用户确实按月分表
        $this->call('command:autotablebuild');
    }
}
