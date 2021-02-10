<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Modules\Bbs\Http\Requests\LoginRequest;
use App\Modules\Bbs\Http\Requests\RegisterRequest;
use App\Modules\Bbs\Services\AuthService;
use App\Traits\Json;

class AuthController extends BbsController
{
    public function __construct(AuthService $authService)
    {
        $this->service = $authService;
    }

    /**
     * 注册
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->all();

        // 注册流程
        if ($user = $this->service->register($data)){
            return $this->successJson($user, $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 登录
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Bbs\AuthException
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        // 登录流程
        $user = $this->service->login($data);

        return $this->successJson($user, '登录成功！');
    }

    /**
     * 获取登录会员信息
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Bbs\AuthTokenException
     */
    public function me()
    {
        return $this->successJson($this->service->me(), '会员信息获取成功！');
    }

    /**
     * 退出登录
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->service->logout();
        return $this->successJson([], '退出成功！');
    }
}
