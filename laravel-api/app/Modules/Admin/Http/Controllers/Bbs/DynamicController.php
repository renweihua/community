<?php

namespace App\Modules\Admin\Http\Controllers\Bbs;

use App\Modules\Admin\Http\Controllers\BaseController;
use App\Modules\Admin\Services\DynamicService;
use Illuminate\Http\JsonResponse;

class DynamicController extends BaseController
{
    public function __construct(DynamicService $articleService)
    {
        $this->service = $articleService;
    }
}
