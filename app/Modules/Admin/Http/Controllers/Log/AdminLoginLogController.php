<?php

namespace App\Modules\Admin\Http\Controllers\Log;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Services\AdminLoginLogService;

class AdminLoginLogController extends BaseController
{
    public function __construct(AdminLoginLogService $adminLoginLogService)
    {
        $this->service = $adminLoginLogService;
    }
}
