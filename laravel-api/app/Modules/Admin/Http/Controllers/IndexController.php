<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Models\MonthModel;
use App\Modules\Admin\Services\IndexService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function __construct(IndexService $indexService)
    {
        $this->service = $indexService;
    }

    public function index(Request $request): JsonResponse
    {
        return $this->successJson($this->service->index());
    }

    /**
     * 按照日志类型的统计图数据
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logsStatistics(Request $request): JsonResponse
    {
        return $this->successJson($this->service->logsStatistics());
    }

    /**
     * 月份表列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonthList(): JsonResponse
    {
        return $this->successJson(MonthModel::getInstance()->getAllMonthes());
    }

    /**
     * 编辑登录管理员信息
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        if ( $this->service->updateAdmin($request) ) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 版本历史记录
     *
     * @return mixed
     */
    public function versionLogs(): JsonResponse
    {
        return $this->successJson($this->service->versionLogs());
    }

    /**
     * 获取服务器状态
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServerStatus(): JsonResponse
    {
        return $this->successJson($this->service->getServerStatus());
    }
}
