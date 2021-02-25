<?php

namespace App\Modules\Admin\Http\Controllers\Rabc;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Http\Requests\Rabc\AdminMenuRequest;
use App\Modules\Admin\Services\AdminMenuService;

class AdminMenuController extends BaseController
{
    public function __construct(AdminMenuService $adminMenuService)
    {
        $this->service = $adminMenuService;
    }

    public function create(AdminMenuRequest $request)
    {
        return $this->createService($request);
    }

    public function update(AdminMenuRequest $request)
    {
        return $this->updateService($request);
    }
}
