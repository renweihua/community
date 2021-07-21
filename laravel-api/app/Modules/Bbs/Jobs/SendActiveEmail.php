<?php

namespace App\Modules\Bbs\Jobs;

use App\Modules\Bbs\Emails\Activation;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

/**
 * 邮件注册成功之后，发送邮件的队列事件
 *
 * Class SendRegisterEmail
 *
 * @package App\Modules\Bbs\Jobs
 */
class SendActiveEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 在超时之前任务可以运行的秒数
     *
     * @var int
     */
    public $timeout = 120;

    public $user;
    public $verify_token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $verify_token)
    {
        $this->user = $user;
        $this->verify_token = $verify_token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 发送邮件
        Mail::to($this->user->user_email)->send(
            new Activation($this->user, $this->verify_token)
        );
    }
}
