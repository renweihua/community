<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Modules\Bbs\Http\Requests\UserIdRequest;
use App\Modules\Bbs\Services\UserService;
use Illuminate\Http\JsonResponse;

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

        if ($detail = $this->service->detail((int)$data['user_id'])) {
            return $this->successJson($detail, $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }
}
