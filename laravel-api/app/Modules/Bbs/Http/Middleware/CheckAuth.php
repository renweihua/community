<?php

namespace App\Modules\Bbs\Http\Middleware;

use App\Constants\UserCacheKeys;
use App\Exceptions\Bbs\AuthException;
use App\Exceptions\Bbs\FailException;
use App\Library\Encrypt\Rsa;
use App\Models\User\User;
use App\Traits\Json;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CheckAuth
{
    use Json;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        if (empty($token)){
            return $this->errorJson('请先登录！', -1);
        }
        $token_user = Rsa::privDecrypt($token);
        if (!$token_user){
            return $this->errorJson('认证失败，请重新登录！', -1);
        }
        // Redis 是否存在key
        if (empty(Redis::connection('token')->get(UserCacheKeys::USER_LOGIN_TOKEN . $token))){
            return $this->errorJson('Token过期，请重新登录（Auth）！', -1);
        }
        try {
            // Token 是否过期
            if (!isset($token_user->expire_time) || $token_user->expire_time <= time()){
                return $this->errorJson('Token过期，请重新登录！', -1);
            }

            $user = User::find($token_user->user_id);
            switch ($user->is_check) {
                case 0:
                    throw new AuthException('该账户已被禁用！', 0, $user->user_id);
                    break;
                case 2:
                    throw new AuthException('异地登录，请重新登录！', 0, $user->user_id);
                    break;
            }

            // 把登录会员信息追加到 request类
            $request->attributes->set('login_user', $user);

        } catch (FailException $e) {
            return $this->errorJson($e->getMessage());
        }

        return $next($request);
    }
}
