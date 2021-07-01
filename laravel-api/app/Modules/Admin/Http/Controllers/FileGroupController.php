<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Http\Requests\FileGroupRequest;
use App\Modules\Admin\Services\FileGroupService;

class FileGroupController extends BaseController
{
    public function __construct(FileGroupService $fileGroupService)
    {
        $this->service = $fileGroupService;
    }

    public function create(FileGroupRequest $request)
    {
        return $this->createService($request);
    }

    public function update(FileGroupRequest $request)
    {
        return $this->updateService($request);
    }
}
