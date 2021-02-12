<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Services\User\SignService;

class SignController extends BbsController
{
    public function __construct(SignService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 每日签到
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn()
    {
        if ($result = $this->service->signIn($this->login_user)){
            return $this->successJson([], $this->service->getError());
        }else{
            return $this->errorJson($this->service->getError());
        }
    }
}
