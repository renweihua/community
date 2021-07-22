<?php

namespace App\Modules\Bbs\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

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
    public function callback($oauth)
    {
        $user = Socialite::driver($oauth)->user();
        var_dump($user);
        exit;

        var_dump($user->token);
        var_dump($user);
    }
}
