<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class OneDragonMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:modular {name} {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new modular.';

    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->argument('module');

        // 自动生成模型
        Artisan::call('module:make-one-dragon-model', [
            'model' => $name,
            'module' => $module,
        ]);

        // 自动生成控制器
        Artisan::call('module:make-one-dragon-controller', [
            'controller' => $name,
            'module' => $module,
        ]);

        // 自动生成验证类
        Artisan::call('module:make-one-dragon-request', [
            'name' => $name . 'Request',
            'module' => $module,
        ]);

        // 自动生成Service
        Artisan::call('module:make-one-dragon-service', [
            'name' => $name . 'Service',
            'module' => $module,
        ]);

        $this->info('modular execution complete.');
    }
}
