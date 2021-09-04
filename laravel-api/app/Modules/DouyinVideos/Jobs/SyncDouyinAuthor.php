<?php

namespace App\Modules\DouyinVideos\Jobs;

use App\Models\Douyin\DouyinAuthor;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class SyncDouyinAuthor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $author;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($author)
    {
        $this->author = $author;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 下载的文件路径
        $path_file_folder = Storage::path('/download');
        // 创建作者的文件夹目录
        $path_file_folder = trim($path_file_folder, '/') . '/' . $this->author->nick_name . '-' .  make_file_folder_name($this->author->unique_id);
        if (!is_dir($path_file_folder)) {
            mkdir($path_file_folder, 0777, true);
        }

        // 重新获取作者的信息【暂时不同步，仅用于记录上一次同步动态的时间】
        if ($this->author instanceof DouyinAuthor){
            // 检测作者是否存在
            $author = $this->author->where('uid', $this->author->uid)->first();
            // 录入作者
            if (!$author){
                $author = $this->createAuthor();
            }else{
                // 上一次同步时间
                $author->update(['last_sync' => time()]);
            }
        }else{
            $author = $this->createAuthor();
        }

        // 分发队列，同步当前作者的动态
        SyncDouyinVideos::dispatch($author, $path_file_folder)
                        ->delay(now()->addMinutes(rand(1, 20 * 3600))) // 延迟分钟数【在20小时内执行完成即可】
                        ->onConnection('database') // job 存储的服务：当前存储mysql
                        ->onQueue('douyin-queue'); // douyin-queue
    }

    protected function createAuthor()
    {
        $this->author['last_sync'] =  time();
        return DouyinAuthor::create($this->author);
    }
}
