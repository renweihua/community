<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Services\User\NotifyService;
use Illuminate\Http\JsonResponse;

class NotifyController extends BbsController
{
    public function __construct(NotifyService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 我的未读消息统计
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unread(): JsonResponse
    {
        $list = $this->service->unread($this->login_user);
        return $this->successJson($list);
    }
}
