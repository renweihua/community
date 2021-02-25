<?php

namespace App\Modules\Bbs\Http\Middleware;

use App\Constants\UserCacheKeys;
use App\Library\Encrypt\Rsa;
use App\Models\User\User;
use App\Traits\Json;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

/**
 * 通过Token获取会员标识【因为有些是接口是无需验证登录，但是如果已登录，需要返回对应的数据标识：如，首页动态是否已赞】
 *
 * Class GetUserByToken
 *
 * @package App\Modules\Bbs\Http\Middleware
 */
class GetUserByToken
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
        var_dump(__CLASS__);

        $token = $request->header('Authorization');
        if (!empty($token)){
            if ($token_user = Rsa::privDecrypt($token)){
                // Redis 是否存在key
                if (empty(Redis::connection('token')->get(UserCacheKeys::USER_LOGIN_TOKEN . $token))){
                    // Token 是否过期
                    if (isset($token_user->expire_time) && $token_user->expire_time > time()){
                        $user = User::find($token_user->user_id);
                        if ($user->is_check == 1){
                            // 把登录会员信息追加到 request类
                            $request->attributes->set('login_user', $user);
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
