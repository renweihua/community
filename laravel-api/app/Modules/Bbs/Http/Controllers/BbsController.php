<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Traits\Json;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;

class BbsController extends Controller
{
    use Json;

    protected $service;

    public function getLoginUser()
    {
        return request()->attributes->get('login_user');
    }

    public function getLoginUserId(): int
    {
        return $this->getLoginUser()->user_id ?? 0;
    }
}
