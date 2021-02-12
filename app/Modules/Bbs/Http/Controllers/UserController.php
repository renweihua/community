<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Modules\Bbs\Http\Requests\UserIdRequest;
use App\Modules\Bbs\Services\DynamicService;
use App\Modules\Bbs\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BbsController
{
    public function __construct(UserService $service)
    {
        parent::__construct();
        $this->service = $service;
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

        if ($detail = $this->service->detail((int)$data['user_id'], $this->login_user)) {
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

        $list = $dynamicService->getDynamics($request, (int)$data['user_id'], (int)$this->login_user);
        return $this->successJson($list);
    }
}
