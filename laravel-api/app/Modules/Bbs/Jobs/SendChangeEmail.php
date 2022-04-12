<?php

namespace App\Modules\Bbs\Jobs;

use App\Modules\Bbs\Emails\Activation;
use App\Modules\Bbs\Emails\ChangeEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

/**
 * 更换邮箱，发送邮件的队列事件
 *
 * Class SendRegisterEmail
 *
 * @package App\Modules\Bbs\Jobs
 */
class SendChangeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 在超时之前任务可以运行的秒数
     *
     * @var int
     */
    public $timeout = 120;

    public $user;
    public $email;
    public $verify_token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $email, $verify_token)
    {
        $this->user = $user;
        $this->email = $email;
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
        Mail::to($this->email)->send(
            new ChangeEmail($this->user, $this->verify_token)
        );
    }
}
