<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Services\TopicService;
use Illuminate\Http\JsonResponse;

class TopicController extends BbsController
{
    public function __construct(TopicService $service)
    {
        $this->service = $service;
    }

    /**
     * 我关注的荟吧列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function follows(): JsonResponse
    {
        $lists = $this->service->getFollows($this->getLoginUserId());
        return $this->successJson($lists);
    }
}
