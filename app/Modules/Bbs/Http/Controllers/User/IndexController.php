<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Http\Requests\User\UpdateRequest;
use App\Modules\Bbs\Services\User\UserService;

class IndexController extends BbsController
{
    public function __construct(UserService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * 个人资料编辑
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\UpdateRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $request->validated();

        if ($this->service->updateUser($this->login_user, $request->all())) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }
}
