<?php

namespace App\Modules\Bbs\Jobs;

use App\Models\User\FollowBbsDynamic;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * 当系统新增动态发布时，给关注会员推送消息
 */
class NewDynamicPushUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dynamic;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dynamic)
    {
        $this->dynamic = $dynamic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (empty(FollowBbsDynamic::count())) return;

        // 查询出要给哪些会员发送新动态通知
        User::select(['user_id', 'user_email'])
            ->with([
                'userInfo' => function($query){
                    $query->select(['user_id', 'nick_name']);
                },
                'userNotifySetting' => function($query){
                    $query->select(['user_id', 'system']);
                }
            ])
            ->chunkById(2, function ($users){
                foreach ($users as $user){
                    // 验证是否关闭了系统消息推送（后期待定）
                    // --- 那其实follow_bbs_dynamics就多余了，`关注新动态发布`存入notity_setting表的system即可

                    // 任务分发：单个会员推送通知
                    NewDynamicPushUserNotify::dispatch($user, $this->dynamic)
                        ->onConnection('database') // job 存储的服务：当前存储mysql
                        ->onQueue('system-notify-queue'); // 队列
                }
        });
    }
}
