<?php

namespace App\Modules\Admin\Http\Controllers\System;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\System\BannerRequest;
use App\Modules\Admin\Services\BannerService;

class BannerController extends BaseController
{
    public function __construct(BannerService $bannerService)
    {
        $this->service = $bannerService;
    }

    public function create(BannerRequest $request)
    {
        return $this->createService($request);
    }

    public function update(BannerRequest $request)
    {
        return $this->updateService($request);
    }
}
