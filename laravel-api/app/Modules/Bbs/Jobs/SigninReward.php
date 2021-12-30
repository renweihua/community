<?php

namespace App\Modules\Bbs\Jobs;

use App\Models\Asktao\DlAdbAll\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

/**
 * 签到奖励：
 *  1.对应的asktao账户奖励银元宝 100
 *
 * Class SendRegisterEmail
 *
 * @package App\Modules\Bbs\Jobs
 */
class SigninReward implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 在超时之前任务可以运行的秒数
     *
     * @var int
     */
    public $timeout = 120;

    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $account = Account::where('account', 'a123')->first();

        // 奖励银元宝 100 silverCoinIncrement
        $account->increment('silver_coin', 100);

        // var_dump('银元宝 100 奖励已发放！');
    }
}
