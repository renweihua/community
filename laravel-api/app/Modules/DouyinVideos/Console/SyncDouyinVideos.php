<?php

namespace App\Modules\DouyinVideos\Console;

use App\Models\Douyin\DouyinAuthor;
use App\Modules\DouyinVideos\Jobs\SyncDouyinAuthor;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SyncDouyinVideos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync_douyin_videos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步抖音作者的视频.';

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
     * @return mixed
     */
    public function handle(DouyinAuthor $douyinAuthor)
    {
        // 每24小时同步一次作者的视频
        $authors = $douyinAuthor->where('last_sync', '<', time() - 24 * 3600)->get();

        foreach ($authors as $author){
            SyncDouyinAuthor::dispatch($author)
                           ->delay(now()->addMinutes(rand(1, 60))) // 延迟分钟数
                           ->onConnection('database') // job 存储的服务：当前存储mysql
                           ->onQueue('douyin-queue'); // douyin-queue 队列
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
