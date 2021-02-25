<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;

class TestController extends BbsController
{
    public function getUserBlackExists()
    {
        return $this->successJson(false);
    }
}
