<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Modules\Bbs\Services\AuthService;
use Cnpscy\Socialite\Facades\Socialite;
use Cnpscy\Socialite\Two\InvalidStateException;
use Illuminate\Http\JsonResponse;

class OauthController extends BbsController
{
    // http://community.cnpscy.com/oauth/github
    public function redirect($oauth): JsonResponse
    {
        return $this->successJson(Socialite::driver($oauth)->redirect()->getTargetUrl());
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback($oauth, AuthService $authService)
    {
        // 无状态认证：stateless 方法可用于禁止会话状态验证
        $user = Socialite::driver($oauth)->stateless()->user();

        try{
            return $this->successJson($authService->oauthLogin($oauth, $user, $this->getLoginUser()));
        }catch (InvalidStateException $e){
            return $this->errorJson($e->getMessage());
        }catch (\Exception $e){
            return $this->errorJson($e->getMessage());
        }
    }
}
