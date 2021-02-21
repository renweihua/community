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
     * 今日签到信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSignByToday()
    {
        $result = $this->service->getSignByToday($this->login_user);
        return $this->successJson($result);
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
    public function getSignsStatusByMonth(MonthRequest $request): JsonResponse
    {
        $data = $request->validated();

        $lists = $this->service->getSignsStatusByMonth($this->login_user, $data['search_month']);
        return $this->successJson($lists, '签到状态获取成功！');
    }

    /**
     * 我的签到记录
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\MonthRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSignsByMonth(MonthRequest $request): JsonResponse
    {
        $data = $request->validated();

        $lists = $this->service->getSignsByMonth($this->login_user, $data['search_month']);
        return $this->successJson($lists, '签到记录获取成功！');
    }
}
