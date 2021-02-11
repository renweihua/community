<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Modules\Bbs\Http\Requests\DynamicIdRequest;
use App\Modules\Bbs\Services\DynamicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DynamicController extends BbsController
{
    public function __construct(DynamicService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 获取动态详情
     *
     * @param  \App\Modules\Bbs\Http\Requests\DynamicIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(DynamicIdRequest $request)
    {
        $data = $request->validated();
        if ($detail = $this->service->detail((int)$data['dynamic_id'])) {
            return $this->successJson($detail, $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 点赞动态
     *
     * @param  \App\Modules\Bbs\Http\Requests\DynamicIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function praise(DynamicIdRequest $request)
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
    public function collection(DynamicIdRequest $request)
    {
        $data = $request->validated();

        if ($result = $this->service->collection($this->user, (int)$data['dynamic_id'])) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

}
