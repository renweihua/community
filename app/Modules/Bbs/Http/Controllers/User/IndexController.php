<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Http\Requests\User\BackgroundCoverRequest;
use App\Modules\Bbs\Http\Requests\User\ChangePasswordByEmailRequest;
use App\Modules\Bbs\Http\Requests\User\ChangePasswordRequest;
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

    /**
     * 更换背景封面图
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\BackgroundCoverRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBackgroundCover(BackgroundCoverRequest $request)
    {
        $request->validated();

        if ($this->service->updateBackgroundCover($this->user, $request->input('background_cover'))) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 更改登录密码
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\ChangePasswordRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $request->validated();

        if ($this->service->changePassword($this->user, $request->input('password'))) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 更改登录密码时，通过邮箱：发送验证码
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMailByChangePassword()
    {
        $this->service->sendMailByChangePassword($this->user);
        return $this->successJson([], '邮件已发送，请及时查看！');
    }

    /**
     * 通过邮箱更改登录密码
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\ChangePasswordByEmailRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassByEmail(ChangePasswordByEmailRequest $request)
    {
        $request->validated();

        if ($this->service->checkEmailCodeAndUpdatePassword($this->user, $request->input('code'), $request->input('password'))) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }
}
