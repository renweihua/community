<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Services\User\NotifyService;
use Illuminate\Http\JsonResponse;

class NotifyController extends BbsController
{
    public function __construct(NotifyService $service)
    {
        $this->service = $service;
    }

    /**
     * 我的未读消息统计
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unread(): JsonResponse
    {
        $list = $this->service->unread($this->getLoginUserId());
        return $this->successJson($list);
    }

    /**
     * 我的{点赞}消息通知
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPraiseByNotify(): JsonResponse
    {
        $lists = $this->service->getPraiseByNotify($this->getLoginUserId());
        return $this->successJson($lists, '获取成功！');
    }

    /**
     * 我的{评论}消息通知
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommentByNotify(): JsonResponse
    {
        $lists = $this->service->getCommentByNotify($this->getLoginUserId());
        return $this->successJson($lists, '获取成功！');
    }


    /**
     * 我的{提醒|系统}消息通知
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSystemByNotify(): JsonResponse
    {
        $lists = $this->service->getSystemByNotify($this->getLoginUserId());
        return $this->successJson($lists, '获取成功！');
    }
}
