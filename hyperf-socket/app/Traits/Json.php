<?php

declare(strict_types = 1);

namespace App\Traits;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Response;

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

    public function success($data = [], $msg = 'success', $other = [])
    {
        $this->data = $data;
        $this->msg = $msg;
        return $this->myAjaxReturn($other);
    }

    public function error($msg = 'error', $status = 0, $data = [], $other = [])
    {
        $this->data = $data;
        $this->msg = $msg;
        $this->status = $status;
        return $this->myAjaxReturn($other);
    }

    public function myAjaxReturn($other)
    {
        $data = array_merge([
            'data' => $this->data,
            'status' => $this->status,
            'msg' => $this->msg
        ], $other);
        // 后台VUE获取的值，后期如何可能改的了的话，就去掉了
        $data['message'] = $data['msg'];
        $data['code'] = $data['status'];
        return $this->response->json($data);
    }
}
