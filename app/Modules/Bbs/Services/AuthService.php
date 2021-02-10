<?php

namespace App\Modules\Bbs\Services;

use App\Exceptions\Bbs\AuthException;
use App\Exceptions\Bbs\AuthTokenException;
use App\Exceptions\InvalidRequestException;
use App\Models\Log\UserLoginLog;
use App\Models\User\User;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class AuthService extends Service
{
    protected $guard = 'user';

    /**
     * 登录流程
     *
     * @param $data
     * @return array
     * @throws AuthException
     * @throws InvalidRequestException
     */
    public function login($data)
    {
        $auth = Auth::guard($this->guard);
        $userInstance = User::getInstance();

        $auth_success = false;
        if ($user_name = $userInstance->getUserByName($data['user_name'])){
            if (hash_verify($data['password'], $user_name->password)){
                $auth_success = true;
                $auth->setUser($user_name);
            }
        }
        if (
            $auth_success == false
            &&
            $user_email = $userInstance->getUserByEmail($data['user_name'])
        )
        {
            if (hash_verify($data['password'], $user_email->password)){
                $auth_success = true;
                $auth->setUser($user_email);
            }
        }
        if (
            $auth_success == false
            &&
            $user_mobile = $userInstance->getUserByMobile($data['user_name'])
        )
        {
            if (hash_verify($data['password'], $user_mobile->password)){
                $auth_success = true;
                $auth->setUser($user_mobile);
            }
        }
        // 如果账户、邮箱、手机号，都验证失败 ，那么登录失败
        if (!$auth_success){
            throw new AuthException('认证失败！');
        }
        $user = $auth->user();
        if (!$user) throw new AuthException('账户不存在！');
        switch ($user->is_check) {
            case 0:
                throw new AuthException('该账户已被禁用！', 0, $user->user_id);
                break;
            case 2:
                throw new AuthException('异地登录，请重新登录！', 0, $user->user_id);
                break;
        }

        // 登录日志
        UserLoginLog::getInstance()->add($user->user_id, 1, '登录成功');

        return $this->respondWithToken($auth->login($user));
    }

    /**
     * 登录管理员信息获取
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws \App\Exceptions\Bbs\AuthTokenException
     */
    public function me()
    {
        if (!$user = Auth::guard($this->guard)->user()){
            throw new AuthTokenException('认证失败！');
        }
        $user->userInfo;
        return $user;
    }

    /**
     * 退出登录
     *
     * @return bool
     */
    public function logout()
    {
        Auth::guard($this->guard)->logout();
        return true;
    }

    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth($this->guard)->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param $token
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_time'   => time() + Auth::guard($this->guard)->factory()->getTTL() * 60
        ];
    }
}
