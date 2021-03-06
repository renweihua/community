<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Middleware\ExecutionMiddleware;
use App\Utils\ExecutionTime;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Response;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

trait Json
{
    /**
     * @Inject
     * @var Response
     */
    protected $response;

    protected $data = [];

    protected $msg = 'success';

    protected $status = 1;

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function successJson($data = [], $msg = 'success', $other = [])
    {
        $this->data = $data;
        $this->msg = $msg;
        return $this->myAjaxReturn($other);
    }

    public function errorJson($msg = 'error', $status = 0, $data = [], $other = [])
    {
        $this->data = $data;
        $this->msg = $msg;
        $this->status = $status;
        return $this->myAjaxReturn($other);
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function setHttpCode(int $http_code): void
    {
        $this->response->withStatus($http_code);
    }

    public function myAjaxReturn($other)
    {
        $data = array_merge([
            'data' => is_null($this->data) ? [] : $this->data,
            'status' => $this->status,
            'msg' => $this->msg
        ], $other);

        // // 后台VUE获取的值，后期如何可能改的了的话，就去掉了
        // $data['message'] = $data['msg'];
        // $data['code'] = $data['status'];

        // 执行时长
        $data['execution_time'] = microtime(true) - ExecutionTime::$start_time;

        return $this->response->json($data);
    }
}
