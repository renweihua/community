<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Modules\Bbs\Http\Requests\DynamicIdRequest;
use App\Modules\Bbs\Http\Requests\DynamicCommentIdRequest;
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
    public function detail(DynamicIdRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if ($detail = $this->service->detail((int)$data['dynamic_id'], $this->login_user)) {
            return $this->successJson($detail, $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 获取动态的评论列表
     *
     * @param  \App\Modules\Bbs\Http\Requests\DynamicIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments(DynamicIdRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $comments = $this->service->getDynamicComments((int)$data['dynamic_id']);
        return $this->successJson($comments);
    }

    /**
     * 评论动态
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\DynamicCommentRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(DynamicCommentRequest $request): JsonResponse
    {
        $request->validated();

        if ($data = $this->service->comment($this->user, $request->all())){
            return $this->successJson($data, $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

}
