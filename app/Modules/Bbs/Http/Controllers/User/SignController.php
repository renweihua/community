<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Http\Requests\User\MonthRequest;
use App\Modules\Bbs\Services\User\SignService;
use Illuminate\Http\JsonResponse;

class SignController extends BbsController
{
    public function __construct(SignService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 每日签到
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn(): JsonResponse
    {
        if ($result = $this->service->signIn($this->login_user)){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 指定月份的签到状态
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\MonthRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSignsByMonth(MonthRequest $request): JsonResponse
    {
        $data = $request->validated();

        $lists = $this->service->getSignsByMonth($this->login_user, $data['month']);
        return $this->successJson($lists, '签到状态获取成功！');
    }
}
