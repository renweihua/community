<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Http\Requests\User\FollowUserRequest;
use App\Modules\Bbs\Services\User\FriendService;
use Illuminate\Http\JsonResponse;

class FriendController extends BbsController
{
    public function __construct(FriendService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 我的关注
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function follows() : JsonResponse
    {
        $lists = $this->service->getFollows($this->login_user);
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

        if ($this->login_user == $data['user_id']) {
            return $this->errorJson('无需关注自己！');
        }

        if ($res = $this->service->setFollow($this->login_user, intval($data['user_id']))) {
            return $this->successJson([], $this->service->getError(), $res);
        } else {
            return $this->errorJson($this->service->getError());
        }
    }
}
