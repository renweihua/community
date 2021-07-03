<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Models\User\User;
use App\Modules\Bbs\Http\Requests\UserIdRequest;
use App\Modules\Bbs\Services\DynamicService;
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
}
