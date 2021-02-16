<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Modules\Bbs\Http\Requests\TopicIdRequest;
use App\Modules\Bbs\Services\TopicService;
use Illuminate\Http\JsonResponse;

class TopicController extends BbsController
{
    public function __construct(TopicService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 荟吧列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(): JsonResponse
    {
        $lists = $this->service->lists();
        return $this->successJson($lists);
    }

    /**
     * 荟吧详情
     *
     * @param  \App\Modules\Bbs\Http\Requests\TopicIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(TopicIdRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if ($detail = $this->service->detail((int)$data['topic_id'], $this->login_user)) {
            return $this->successJson($detail, $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }
}
