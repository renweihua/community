<?php

declare(strict_types = 1);

namespace App\Traits;

trait Json
{
    protected $http_code = 200;

    public function successJson($data = [], $msg = 'success', $other = [])
    {
        return $this->myAjaxReturn(array_merge(['data' => $data, 'msg' => $msg, 'status' => 1], $other));
    }

    public function errorJson($msg = 'error', $status = 0, $data = [], $other = [])
    {
        return $this->myAjaxReturn(array_merge(['msg' => $msg, 'status' => $status, 'data' => $data], $other));
    }

    public function setHttpCode(int $http_code): void
    {
        $this->http_code = $http_code;
    }

    /**
     * [myAjaxReturn]
     * @author:cnpscy <[2278757482@qq.com]>
     * @chineseAnnotation:API接口返回格式统一
     * @englishAnnotation:
     * @version:1.0
     * @param              [type] $data [description]
     */
    public function myAjaxReturn($data)
    {
        $data['data'] = $data['data'] ?? [];
        $data['status'] = intval($data['status'] ?? (empty($data['data']) ? 0 : 1));
        $data['msg'] = $data['msg'] ?? (empty($data['status']) ? '数据不存在！' : 'success');
        $data['execution_time'] = microtime(true) - LARAVEL_START;
        return response()->json($data, $this->http_code);
    }
}
