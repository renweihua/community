<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Models\Dynamic\Dynamic;
use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Http\Requests\DynamicIdRequest;
use App\Modules\Bbs\Http\Requests\User\DynamicCommentIdRequest;
use App\Modules\Bbs\Http\Requests\User\DynamicCommentRequest;
use App\Modules\Bbs\Http\Requests\User\DynamicRequest;
use App\Modules\Bbs\Services\User\DynamicService;
use Illuminate\Http\JsonResponse;

class DynamicController extends BbsController
{
    public function __construct(DynamicService $service)
    {
        $this->service = $service;
    }

    /**
     * 发布动态
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\DynamicRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function push(DynamicRequest $request) : JsonResponse
    {
        $request->validated();

        if ($result = $this->service->push($this->getLoginUserId(), $request->all())) {
            return $this->successJson($result, $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 编辑动态
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\DynamicRequest  $request
     * @param                                                      $dynamic_id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\InvalidRequestException
     */
    public function update(DynamicRequest $request, $dynamic_id) : JsonResponse
    {
        $request->validated();

        if ($result = $this->service->update($this->getLoginUserId(), $dynamic_id, $request->all())) {
            return $this->successJson($result, $this->service->getError());
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
    public function praise(DynamicIdRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if ($result = $this->service->praise($this->getLoginUserId(), (int)$data['dynamic_id'])) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 我的收藏
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function collections() : JsonResponse
    {
        $lists = $this->service->getCollections($this->getLoginUserId());
        return $this->successJson($lists);
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

        if ($result = $this->service->collection($this->getLoginUserId(), (int)$data['dynamic_id'])) {
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

        if ($data = $this->service->comment($this->getLoginUserId(), $request->all())) {
            return $this->successJson($data, $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 删除评论
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\DynamicCommentIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(DynamicCommentIdRequest $request) : JsonResponse
    {
        $request->validated();

        if ($result = $this->service->deleteComment($this->getLoginUserId(), $request->input('comment_id'))) {
            return $this->successJson($result, $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    public function setDynamicFiled(DynamicIdRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if ($result = $this->service->setDynamicFiled($this->getLoginUser(), (int)$data['dynamic_id'], (string)$data['change_field'], $data['change_value'])) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }
}
