<?php

namespace App\Modules\Bbs\Jobs;

use App\Models\Asktao\DlAdbAll\CostLog;
use App\Models\Asktao\UserAsktaoAccount;
use App\Models\Log\WebLog;
use App\Models\User\UserSignReward;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * 异步录入访问日志
 * Class AsyncWebRecordLog
 *
 * @package App\Modules\Bbs\Jobs
 */
class AsyncWebRecordLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 在超时之前任务可以运行的秒数
     *
     * @var int
     */
    public $timeout = 120;

    public $record;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($record)
    {
        $this->record = $record;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        WebLog::create($this->record);
    }
}
