<?php

namespace App\Modules\Admin\Http\Controllers\Log;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Services\AdminLogService;

class AdminLogController extends BaseController
{
    public function __construct(AdminLogService $adminLogService)
    {
        $this->service = $adminLogService;
    }
}
