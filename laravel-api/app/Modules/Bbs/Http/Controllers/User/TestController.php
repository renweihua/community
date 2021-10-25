<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use Illuminate\Http\JsonResponse;

class TestController extends BbsController
{
    public function getUserBlackExists(): JsonResponse
    {
        return $this->successJson(false);
    }
}
