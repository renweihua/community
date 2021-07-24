<?php

namespace App\Modules\Bbs\Http\Controllers;

use App\Modules\Bbs\Services\AuthService;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class OauthController extends BbsController
{
    // http://community.cnpscy.com/oauth/github
    public function redirect($oauth)
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
        $user = Socialite::driver($oauth)->user();

        try{
            return $this->successJson($authService->oauthLogin($oauth, $user));
        }catch (InvalidStateException $e){
            var_dump($e->getMessage());
            var_dump($e->getCode());
            var_dump($e->getFile());
        }catch (\Exception $e){
            var_dump($e->getMessage());
            var_dump($e->getCode());
            var_dump($e->getFile());
        }
    }
}
