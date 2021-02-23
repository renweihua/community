<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Services\User\LoginLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginLogController extends BbsController
{
    /**
     * 登录日志记录
     *
     * @param  \Illuminate\Http\Request                        $request
     * @param  \App\Modules\Bbs\Services\User\LoginLogService  $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListsByMonth(Request $request, LoginLogService $service): JsonResponse
    {
        $lists = $service->getListsByMonth($this->login_user, $request->input('search_month', ''));
        return $this->successJson($lists, '登录日志获取成功！');
    }
}
