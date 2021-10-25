<?php

namespace App\Modules\Admin\Http\Controllers\Rabc;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Rabc\AdminRoleRequest;
use App\Modules\Admin\Services\AdminRoleService;
use Illuminate\Http\JsonResponse;

class AdminRoleController extends BaseController
{
    public function __construct(AdminRoleService $adminRoleService)
    {
        $this->service = $adminRoleService;
    }

    public function create(AdminRoleRequest $request): JsonResponse
    {
        return $this->createService($request);
    }

    public function update(AdminRoleRequest $request): JsonResponse
    {
        return $this->updateService($request);
    }
}
