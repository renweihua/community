<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Modules\Bbs\Services\IndexService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexController extends BbsController
{
    public function __construct(IndexService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 首页：发现
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function discover(Request $request) : JsonResponse
    {
        $lists = $this->service->discover($this->user, $request);
        return $this->successJson($lists);
    }

    /**
     * 首页：关注
     */
    public function follows() : JsonResponse
    {
        $lists = $this->service->follows($this->login_user_id);
        return $this->successJson($lists);
    }
}
