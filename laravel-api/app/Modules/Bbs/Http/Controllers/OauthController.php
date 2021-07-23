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
        return Socialite::driver($oauth)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback($oauth, AuthService $authService)
    {
        $user = Socialite::driver($oauth)->user();

        var_dump($authService->oauthLogin($oauth, $user));
        exit;

        try{
        }catch (InvalidStateException $e){
            var_dump($e->getMessage());
            var_dump($e->getCode());
            var_dump($e->getFile());
        }
    }
}
