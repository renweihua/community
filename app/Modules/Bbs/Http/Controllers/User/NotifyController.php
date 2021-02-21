<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Services\User\NotifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotifyController extends BbsController
{
    public function __construct(NotifyService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 我的未读消息统计
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unread(): JsonResponse
    {
        $list = $this->service->unread($this->login_user);
        return $this->successJson($list);
    }

    /**
     * 我的{点赞}消息通知
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPraiseByNotify()
    {
        $lists = $this->service->getPraiseByNotify($this->login_user);
        return $this->successJson($lists, '获取成功！');
    }

    /**
     * 我的{评论}消息通知
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommentByNotify()
    {
        $lists = $this->service->getCommentByNotify($this->login_user);
        return $this->successJson($lists, '获取成功！');
    }


    /**
     * 我的{提醒|系统}消息通知
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSystemByNotify()
    {
        $lists = $this->service->getSystemByNotify($this->login_user);
        return $this->successJson($lists, '获取成功！');
    }
}
