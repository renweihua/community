<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Models\User\User;
use App\Modules\Bbs\Http\Requests\UserIdRequest;
use App\Modules\Bbs\Services\DynamicService;
use App\Modules\Bbs\Services\User\FriendService;
use App\Modules\Bbs\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BbsController
{
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * 会员列表
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request): JsonResponse
    {
        $users = $this->service->lists($request->all(), $request->get('limit', 10));
        return $this->successJson($users);
    }

    /**
     * 指定会员详情信息
     *
     * @param  \App\Modules\Bbs\Http\Requests\UserIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(UserIdRequest $request) : JsonResponse
    {
        $data = $request->validated();

        if ($detail = $this->service->detail((int)$data['user_id'], $this->getLoginUserId())) {
            return $this->successJson($detail, $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 通过UUID获取会员详情
     *
     * @param $user_uuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uuid($user_uuid): JsonResponse
    {
        if ($detail = $this->service->detail($user_uuid, $this->getLoginUserId())) {
            return $this->successJson($detail, $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 指定会员的动态列表
     *
     * @param  \App\Modules\Bbs\Http\Requests\UserIdRequest  $request
     * @param  \App\Modules\Bbs\Services\DynamicService      $dynamicService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dynamics(UserIdRequest $request, DynamicService $dynamicService) : JsonResponse
    {
        $data = $request->validated();

        $list = $dynamicService->getDynamicsByUser($request, (int)$data['user_id'], (int)$this->getLoginUserId());
        return $this->successJson($list);
    }

    /**
     * 检测会员账户是否已被注册
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function exists(Request $request)
    {
        $user = User::getInstance();
        if ($request->has('user_email')) {
            return $user->getUserByEmail($request->get('user_email')) ? $this->successJson() : $this->errorJson('');
        }

        if ($request->has('user_name')) {
            return $user->getUserByName($request->get('user_name')) ? $this->successJson() : $this->errorJson('');
        }

        if ($request->has('user_mobile')) {
            return $user->getUserByMobile($request->get('user_mobile')) ? $this->successJson() : $this->errorJson('');
        }

        \abort(400);
    }

    /**
     * 邮箱激活流程
     *
     * @param $verify_token
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function activeEmail($verify_token)
    {
        $result = $this->service->verifyEmailToken($verify_token);
        if (!$result){
            \abort(400, $this->service->getError());
        }
        // 激活成功，跳转Web的指定页面即可
        return redirect(config('app.site_url').'?active-success=yes&type=register');
    }

    /**
     * 激活变更之后的邮箱
     *
     * @param $verify_token
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function activeChangeEmail($verify_token)
    {
        $result = $this->service->verifyEmailToken($verify_token, true);
        if (!$result){
            \abort(400, $this->service->getError());
        }
        // 激活成功，跳转Web的指定页面即可
        return redirect(config('app.site_url').'?active-success=yes&type=email');
    }

    /**
     * 我的关注
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function follows($user_id, FriendService $service) : JsonResponse
    {
        $lists = $service->getFollows($user_id);
        return $this->successJson($lists);
    }

    /**
     * 我的粉丝
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fans($user_id, FriendService $service) : JsonResponse
    {
        $lists = $service->getFans($user_id);
        return $this->successJson($lists);
    }
}
