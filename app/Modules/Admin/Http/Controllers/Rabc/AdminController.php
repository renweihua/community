<?php

namespace App\Modules\Admin\Http\Controllers\Rabc;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Rabc\AdminRequest;
use App\Modules\Admin\Services\AdminService;

class AdminController extends BaseController
{
    public function __construct(AdminService $adminService)
    {
        $this->service = $adminService;
    }

    public function create(AdminRequest $request)
    {
        return $this->createService($request);
    }

    public function update(AdminRequest $request)
    {
        return $this->updateService($request);
    }
}
