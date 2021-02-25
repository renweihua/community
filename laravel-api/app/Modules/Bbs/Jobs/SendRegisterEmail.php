<?php

namespace App\Modules\Bbs\Jobs;

use App\Modules\Bbs\Notifications\EmailRegister;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

/**
 * 邮件注册成功之后，发送邮件的队列事件
 *
 * Class SendRegisterEmail
 *
 * @package App\Modules\Bbs\Jobs
 */
class SendRegisterEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 在超时之前任务可以运行的秒数
     *
     * @var int
     */
    public $timeout = 120;

    public $user_email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_email)
    {
        $this->user_email = $user_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::route('mail', $this->user_email)->notify(new EmailRegister());
    }
}
