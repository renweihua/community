<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Http\Requests\User\FollowUserRequest;
use App\Modules\Bbs\Http\Requests\User\SpecialUserRequest;
use App\Modules\Bbs\Services\User\FriendService;
use Illuminate\Http\JsonResponse;

class FriendController extends BbsController
{
    public function __construct(FriendService $service)
    {
        $this->service = $service;
    }

    public function friends()
    {
        // 此项目没有好友申请流程等功能，暂时先返回100条会员
        return $this->successJson($this->service->friends($this->getLoginUserId()));
    }

    /**
     * 我的关注
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function follows() : JsonResponse
    {
        $lists = $this->service->getFollows($this->getLoginUserId());
        return $this->successJson($lists);
    }

    /**
     * 我的粉丝
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fans() : JsonResponse
    {
        $lists = $this->service->getFans($this->getLoginUserId());
        return $this->successJson($lists);
    }

    /**
     * 关注指定会员
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\FollowUserRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(FollowUserRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if ($this->getLoginUserId() == $data['user_id']) {
            return $this->errorJson('无需关注自己！');
        }

        if ($res = $this->service->setFollow($this->getLoginUserId(), intval($data['user_id']))) {
            return $this->successJson([], $this->service->getError(), $res);
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 指定会员，设置是否特别关注
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\SpecialUserRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setSpecial(SpecialUserRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if ($this->getLoginUserId() == $data['user_id']) {
            return $this->errorJson('无需特别关注自己！');
        }

        if ($res = $this->service->setSpecial($this->getLoginUserId(), intval($data['user_id']), $request->input('is_cancel', 0))) {
            return $this->successJson([], $this->service->getError(), $res);
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 指定会员，设置是否黑名单
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\SpecialUserRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setBlacklist(SpecialUserRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if ($this->getLoginUserId() == $data['user_id']) {
            return $this->errorJson('无需拉黑自己！');
        }

        if ($res = $this->service->setBlacklist($this->getLoginUserId(), intval($data['user_id']), $request->input('is_cancel', 0))) {
            return $this->successJson([], $this->service->getError(), $res);
        } else {
            return $this->errorJson($this->service->getError());
        }
    }
}
