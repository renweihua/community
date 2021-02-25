<?php

namespace App\Modules\Admin\Http\Controllers\System;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\System\VersionRequest;
use App\Modules\Admin\Services\VersionService;

class VersionController extends BaseController
{
    public function __construct(VersionService $versionService)
    {
        $this->service = $versionService;
    }

    public function create(VersionRequest $request)
    {
        return $this->createService($request);
    }

    public function update(VersionRequest $request)
    {
        return $this->updateService($request);
    }
}
