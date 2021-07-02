<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\Rabc\AdminMenu;
use App\Modules\Admin\Http\Requests\LoginRequest;
use App\Modules\Admin\Services\AuthService;

class AuthController extends BaseController
{
    public function __construct(AuthService $authService)
    {
        $this->service = $authService;
    }

    /**
     * 登录流程
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Admin\AuthException
     * @throws \App\Exceptions\InvalidRequestException
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        // 登录流程
        $token = $this->service->login($data);

        return $this->successJson($token);
    }

    /**
     * 获取登录管理员信息
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Admin\AuthException
     */
    public function me()
    {
        if (\request()->getMethod() == 'OPTIONS'){
            return $this->successJson();
        }

        return $this->successJson($this->service->me());
    }

    /**
     * 退出登录
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        return $this->successJson($this->service->logout());
    }

    public function getRabcList()
    {
        return $this->successJson($this->service->getRabcList());
        
        // 临时测试数据
        return $this->successJson(list_to_tree(AdminMenu::getInstance()->getAllMenus()->toArray()));
    }
}
