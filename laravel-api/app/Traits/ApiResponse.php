<?php

declare(strict_types = 1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * 拦截http状态码
     * @var int
     */
    protected $http_code = Response::HTTP_OK;

    protected $status = 1;

    protected $data = [];

    protected $msg = 'success';

    protected $header = [];

    public function successJson($data = [], $msg = 'success', $other = [], array $header = [])
    {
        $this->data = $data;
        $this->msg = $msg;
        $this->status = 1;
        $this->header = $header;

        return $this->json($other);
    }

    public function errorJson($msg = 'error', $data = [], $other = [], array $header = [])
    {
        $this->data = $data;
        $this->msg = $msg;
        $this->status = 1;
        $this->header = $header;

        return $this->json($other);
    }

    public function setHttpCode(int $http_code): void
    {
        $this->http_code = $http_code;
    }

    /**
     * API接口返回格式统一
     *
     * @param  array  $data
     * @param  array  $header
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function json(array $other = [], array $header = []): JsonResponse
    {
        $data = array_merge([
            'data' => $this->data,
            'status' => $this->status,
            'msg' => $this->msg,
            'execution_time' => microtime(true) - LARAVEL_START,
        ], $other);

        // 如果设置的header的Token返回，那么追加参数
        if ($authorization = request()->header('new_authorization')) $header['Authorization'] = $authorization;
        // JSON_UNESCAPED_UNICODE 256：Json不要编码Unicode
        return response()->json($data, $this->http_code, $header, 256);
    }
}
