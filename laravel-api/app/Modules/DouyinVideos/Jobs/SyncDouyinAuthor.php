<?php

namespace App\Modules\DouyinVideos\Jobs;

use App\Models\Douyin\DouyinAuthor;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
        SyncDouyinVideos::dispatch($author)
                        ->delay(now()->addMinutes(rand(0, 60))) // 延迟分钟数
                        ->onConnection('database') // job 存储的服务：当前存储mysql
                        ->onQueue('douyin-queue'); // douyin-queue
    }

    protected function createAuthor()
    {
        $this->author['last_sync'] =  time();
        return DouyinAuthor::create($this->author);
    }
}
