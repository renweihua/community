<?php

namespace App\Modules\Admin\Http\Controllers\Rabc;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Rabc\AdminMenuRequest;
use App\Modules\Admin\Services\AdminMenuService;
use Illuminate\Http\JsonResponse;

class AdminMenuController extends BaseController
{
    public function __construct(AdminMenuService $adminMenuService)
    {
        $this->service = $adminMenuService;
    }

    public function create(AdminMenuRequest $request): JsonResponse
    {
        return $this->createService($request);
    }

    public function update(AdminMenuRequest $request): JsonResponse
    {
        return $this->updateService($request);
    }
}
