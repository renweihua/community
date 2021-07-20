<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Http\Requests\User\BackgroundCoverRequest;
use App\Modules\Bbs\Http\Requests\User\ChangePasswordByEmailRequest;
use App\Modules\Bbs\Http\Requests\User\ChangePasswordRequest;
use App\Modules\Bbs\Http\Requests\User\UpdateAvatarRequest;
use App\Modules\Bbs\Http\Requests\User\UpdateRequest;
use App\Modules\Bbs\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class IndexController extends BbsController
{
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * 个人资料编辑
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\UpdateRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        $request->validated();

        if ($this->service->updateUser($this->getLoginUserId(), $request->all())) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }

    /**
     * 更换会员头像
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\UpdateAvatarRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvatar(UpdateAvatarRequest $request): JsonResponse
    {
        $request->validated();

        if ($this->service->updateAvatarCover($this->getLoginUser(), $request->input('user_avatar'))) {
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
    public function updateBackgroundCover(BackgroundCoverRequest $request): JsonResponse
    {
        $request->validated();

        if ($this->service->updateBackgroundCover($this->getLoginUser(), $request->input('background_cover'))) {
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
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $request->validated();

        if ($this->service->changePassword($this->getLoginUser(), $request->input('password'))) {
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
    public function sendMailByChangePassword(): JsonResponse
    {
        $this->service->sendMailByChangePassword($this->getLoginUser());
        return $this->successJson([], '邮件已发送，请及时查看！');
    }

    /**
     * 通过邮箱更改登录密码
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\ChangePasswordByEmailRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassByEmail(ChangePasswordByEmailRequest $request): JsonResponse
    {
        $request->validated();

        if ($this->service->checkEmailCodeAndUpdatePassword($this->getLoginUser(), $request->input('code'), $request->input('password'))) {
            return $this->successJson([], $this->service->getError());
        } else {
            return $this->errorJson($this->service->getError());
        }
    }
}
