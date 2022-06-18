<?php

namespace App\Modules\Bbs\Http\Controllers\User;

use App\Modules\Bbs\Http\Controllers\BbsController;
use App\Modules\Bbs\Http\Requests\User\BackgroundCoverRequest;
use App\Modules\Bbs\Http\Requests\User\ChangeEmailRequest;
use App\Modules\Bbs\Http\Requests\User\ChangePasswordByEmailRequest;
use App\Modules\Bbs\Http\Requests\User\ChangePasswordRequest;
use App\Modules\Bbs\Http\Requests\User\ChangeUserNameRequest;
use App\Modules\Bbs\Http\Requests\User\UpdateAvatarRequest;
use App\Modules\Bbs\Http\Requests\User\UpdateRequest;
use App\Modules\Bbs\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        $this->service->updateUser($this->getLoginUserId(), $request->all());

        return $this->successJson([], $this->service->getError());
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

        $this->service->updateAvatarCover($this->getLoginUser(), $request->input('user_avatar'));

        return $this->successJson([], $this->service->getError());
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

        $this->service->updateBackgroundCover($this->getLoginUser(), $request->input('background_cover'));

        return $this->successJson([], $this->service->getError());
    }

    /**
     * 编辑扩展信息
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function extend(Request $request): JsonResponse
    {
        $this->service->updateExtend($this->getLoginUser(), $request->input());
        return $this->successJson([], $this->service->getError());
    }

    /**
     * 更改登录账户
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\ChangeUserNameRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeUserName(ChangeUserNameRequest $request): JsonResponse
    {
        $request->validated();

        $this->service->changeUserName($this->getLoginUser(), $request->input('user_name'));

        return $this->successJson([], $this->service->getError());
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

        $this->service->changePassword($this->getLoginUser(), $request->input('password'));

        return $this->successJson([], $this->service->getError());
    }

    /**
     * 更改登录密码时，通过邮箱：发送验证码
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMailByChangePassword(Request $request): JsonResponse
    {
        $this->service->sendMailByChangePassword($request->email);
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

        $this->service->checkEmailCodeAndUpdatePassword($request->email, $request->input('code'), $request->input('password'));

        return $this->successJson([], $this->service->getError());
    }

    /**
     * 更改邮箱，发送邮件
     *
     * @param  \App\Modules\Bbs\Http\Requests\User\ChangeEmailRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeEmail(ChangeEmailRequest $request): JsonResponse
    {
        $data = $request->validated();

        $this->service->changeEmail($this->getLoginUser(), $data['user_email']);

        return $this->successJson([], $this->service->getError());
    }
}
