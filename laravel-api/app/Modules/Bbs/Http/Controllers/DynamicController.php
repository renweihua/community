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
        $this->service = $service;
    }

    /**
     * 动态列表
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request): JsonResponse
    {
        $lists = $this->service->lists($request, $this->getLoginUserId());
        return $this->successJson($lists);
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

        $detail = $this->service->detail((int)$data['dynamic_id'], $this->getLoginUserId());

        return $this->successJson($detail, $this->service->getError());
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
     * 获取动态的评论列表
     *
     * @param  \App\Modules\Bbs\Http\Requests\DynamicIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments(DynamicIdRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $comments = $this->service->getDynamicComments((int)$data['dynamic_id'], $this->getLoginUserId(), $request->input('is_pc', 0));
        return $this->successJson($comments);
    }

    /**
     * 加载指定评论，更多的回复列表
     *
     * @param  \App\Modules\Bbs\Http\Requests\DynamicCommentIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadMoreComments(DynamicCommentIdRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $comments = $this->service->loadMoreReplyByComments((int)$data['comment_id']);
        return $this->successJson($comments);
    }
}
