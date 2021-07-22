<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Modules\Bbs\Http\Requests\TopicIdRequest;
use App\Modules\Bbs\Services\TopicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TopicController extends BbsController
{
    public function __construct(TopicService $service)
    {
        $this->service = $service;
    }

    /**
     * 荟吧列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request): JsonResponse
    {
        $lists = $this->service->lists($request->input('limit', -1));
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

        if ($detail = $this->service->detail((int)$data['topic_id'], $this->getLoginUserId())) {
            return $this->successJson($detail, $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 指定荟吧的动态列表
     *
     * @param  \App\Modules\Bbs\Http\Requests\TopicIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dynamics(TopicIdRequest $request): JsonResponse
    {
        $request->validated();

        $lists = $this->service->dynamics($request, $this->getLoginUserId());
        return $this->successJson($lists);
    }

    /**
     * 关注指定荟吧
     *
     * @param  \App\Modules\Bbs\Http\Requests\TopicIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(TopicIdRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if ($res = $this->service->setFollow($this->getLoginUserId(), (int)$data['topic_id'])) {
            return $this->successJson([], $this->service->getError(), $res);
        } else {
            return $this->errorJson($this->service->getError());
        }
    }
}
