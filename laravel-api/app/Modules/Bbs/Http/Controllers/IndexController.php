<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Modules\Bbs\Services\DynamicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexController extends BbsController
{
    public function __construct(DynamicService $service)
    {
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
        $lists = $this->service->discover($this->getLoginUserId(), $request);
        return $this->successJson($lists);
    }

    /**
     * 首页：关注
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function follows(Request $request) : JsonResponse
    {
        $lists = $this->service->follows($this->getLoginUserId());
        return $this->successJson($lists);
    }
}
