<?php

namespace App\Modules\Bbs\Http\Middleware;

use App\Constants\UserCacheKeys;
use App\Exceptions\Bbs\AuthException;
use App\Exceptions\Bbs\FailException;
use App\Library\Encrypt\Rsa;
use App\Models\User\User;
use App\Models\User\UserInfo;
use App\Modules\Bbs\Services\UserLoginRedisService;
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
        $redis = Redis::connection('token')->client();
        $token_user_info = json_decode($redis->get(UserCacheKeys::USER_LOGIN_TOKEN . $token));
        if (empty($token_user_info)){
            return $this->errorJson('Token过期，请重新登录（Auth）！', -1);
        }
        /**
         * 关于Token的续签与过期操作，过期时长设置时多加20分钟：
         *  1.10分钟内将要过期的，自动更新过期时间 === 要么重新设置Token的过期时长
         *  2.过期15分钟以内的，生成新的Token并返回 === 头部返回新的Token，前端读取并设置新的Token
         */
        try {
            // Token 是否过期
            if (!isset($token_user->expires_time) || $token_user->expires_time <= time()){
                // 过期15分钟以内的，生成新的Token并返回
                if ($token_user->expires_time <= time() && $token_user->expires_time + 15 * 60 > time()){
                    $userLoginRedisService = UserLoginRedisService::getInstance();
                    $token = $userLoginRedisService->getUserToken($token_user->user_id, $expires_time);

                    // 获取会员的基本信息
                    $user_info = UserInfo::select('user_id', 'nick_name', 'user_avatar')->find($token_user->user_id);

                    // Token存入Redis
                    $userLoginRedisService->saveUserToken([
                        'user_id' => $user_info->user_id,
                        'nick_name' => $user_info->nick_name,
                        'user_avatar' => $user_info->user_avatar,
                        'login_time' => time(),
                        'expires_time' => $expires_time,
                    ], $token);

                    // 设置头部的Token
                    $request->headers->set('new_authorization', $token);
                }else{
                    return $this->errorJson('Token过期，请重新登录！', -1);
                }
            }
            // 10分钟以内将过期的Token，自动续时【说明会员是有在请求API，属于活跃用户的】
            if ($token_user->expires_time - 10 * 60 < time()){
                // 设置新的过期时长
                $token_user_info->expires_time = $token_user->expires_time + UserCacheKeys::KEY_DEFAULT_TIMEOUT;
                // 更新Token的过期时间，并更新内容
                $redis->set(UserCacheKeys::USER_LOGIN_TOKEN . $token, my_json_encode($token_user_info), UserCacheKeys::KEY_DEFAULT_TIMEOUT);
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
