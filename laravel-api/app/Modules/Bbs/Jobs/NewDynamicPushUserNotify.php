<?php

namespace App\Modules\Bbs\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NewDynamicPushUserNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $dynamic;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $dynamic)
    {
        $this->user = $user;
        $this->dynamic = $dynamic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

    }
}
