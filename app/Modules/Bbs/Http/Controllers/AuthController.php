<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Modules\Bbs\Http\Requests\LoginRequest;
use App\Modules\Bbs\Services\AuthService;

class AuthController extends BbsController
{
    public function __construct(AuthService $authService)
    {
        $this->service = $authService;
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        // 登录流程
        $token = $this->service->login($data);

        return $this->successJson($token, '登录成功！');
    }

    public function me()
    {
        return $this->successJson($this->service->me(), '会员信息获取成功！');
    }

    public function logout()
    {
        $this->service->logout();
        return $this->successJson([], '退出成功！');
    }
}
