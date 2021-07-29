<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Admin\Http\Requests\FileGroupRequest;
use App\Modules\Admin\Services\FileGroupService;
use Illuminate\Http\JsonResponse;

class FileGroupController extends BaseController
{
    public function __construct(FileGroupService $fileGroupService)
    {
        $this->service = $fileGroupService;
    }

    public function create(FileGroupRequest $request): JsonResponse

    {
        return $this->createService($request);
    }

    public function update(FileGroupRequest $request): JsonResponse
    {
        return $this->updateService($request);
    }
}
