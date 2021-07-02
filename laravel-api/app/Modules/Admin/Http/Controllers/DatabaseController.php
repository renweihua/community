<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Services\DatabaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DatabaseController extends BaseController
{
    public function __construct(DatabaseService $service)
    {
        $this->service = $service;
    }

    /**
     * 数据表列表及相关统计
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->successJson($this->service->lists($request->all()));
    }

    /**
     * 批量备份指定的数据表
     *
     * @param  \Illuminate\Http\Request       $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function backupsTables(Request $request): JsonResponse
    {
        set_time_limit(0);//防止超时
        if ($result = $this->service->backupsTables($request->tables_list)){
            return $this->successJson($result, $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 备份记录列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function backups(): JsonResponse
    {
        return $this->successJson($this->service->getBackupRecords());
    }

    /**
     * 删除指定的备份记录，并删除对应的备份文件
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBackup(Request $request): JsonResponse
    {
        if ($this->service->deleteBackup($request->input('backup_id'))){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }
}
