<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Http\Requests\User\DynamicCommentIdRequest;
use App\Modules\Bbs\Services\User\CommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends BbsController
{
    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    /**
     * 点赞评论
     *
     * @param  \App\Modules\Bbs\Http\Requests\DynamicCommentIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function praise(DynamicCommentIdRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if ($result = $this->service->praise($this->getLoginUserId(), (int)$data['comment_id'])) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }
}
