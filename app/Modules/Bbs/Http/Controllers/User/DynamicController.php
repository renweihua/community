<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Http\Requests\DynamicIdRequest;
use App\Modules\Bbs\Http\Requests\User\DynamicCommentRequest;
use App\Modules\Bbs\Services\User\DynamicService;
use Illuminate\Http\JsonResponse;

class DynamicController extends BbsController
{
    public function __construct(DynamicService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 获取指定动态的点赞人员记录
     *
     * @param  \App\Modules\Bbs\Http\Requests\DynamicIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPraises(DynamicIdRequest $request): JsonResponse
    {
        $data = $request->validated();

        $lists = $this->service->getPraises((int)$data['dynamic_id']);
        return $this->successJson($lists, $this->service->getError());
    }

    /**
     * 点赞动态
     *
     * @param  \App\Modules\Bbs\Http\Requests\DynamicIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function praise(DynamicIdRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if ($result = $this->service->praise($this->user, (int)$data['dynamic_id'])) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 收藏动态
     *
     * @param  \App\Modules\Bbs\Http\Requests\DynamicIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function collection(DynamicIdRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if ($result = $this->service->collection($this->user, (int)$data['dynamic_id'])) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 评论动态
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\DynamicCommentRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(DynamicCommentRequest $request) : JsonResponse
    {
        $request->validated();

        if ($data = $this->service->comment($this->user, $request->all())) {
            return $this->successJson($data, $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }
}
