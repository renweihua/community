<?php

namespace App\Modules\Admin\Http\Controllers\Bbs;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Bbs\DynamicCheckRequest;
use App\Modules\Admin\Http\Requests\Bbs\DynamicIdRequest;
use App\Modules\Admin\Http\Requests\Bbs\DynamicSetTopRequest;
use App\Modules\Admin\Services\DynamicService;
use Illuminate\Http\JsonResponse;

class DynamicController extends BaseController
{
    public function __construct(DynamicService $articleService)
    {
        $this->service = $articleService;
    }

    /**
     * 动态`审核`
     *
     * @param  \App\Modules\Admin\Http\Requests\Bbs\DynamicCheckRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\InvalidRequestException
     * @throws \Throwable
     */
    public function check(DynamicCheckRequest $request):JsonResponse
    {
        $this->service->check($this->getLoginUserId(), $request);

        return $this->successJson([], $this->service->getError());
    }

    /**
     * 动态`置顶`
     *
     * @param  \App\Modules\Admin\Http\Requests\Bbs\DynamicSetTopRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\InvalidRequestException
     * @throws \Throwable
     */
    public function setTop(DynamicSetTopRequest $request):JsonResponse
    {
        $this->service->setTop($this->getLoginUserId(), $request);

        return $this->successJson([], $this->service->getError());
    }

    /**
     * 动态`加精`
     *
     * @param  \App\Modules\Admin\Http\Requests\Bbs\DynamicIdRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\InvalidRequestException
     * @throws \Throwable
     */
    public function excellent(DynamicIdRequest $request):JsonResponse
    {
        $this->service->excellent($this->getLoginUserId(), $request);

        return $this->successJson([], $this->service->getError());
    }
}
