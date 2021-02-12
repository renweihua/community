<?php

namespace App\Modules\Bbs\Services;

use App\Exceptions\Bbs\AuthException;
use App\Exceptions\Bbs\AuthTokenException;
use App\Exceptions\Bbs\FailException;
use App\Models\Log\UserLoginLog;
use App\Models\User\User;
use App\Models\User\UserInfo;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthService extends Service
{
    protected $guard = 'user';

    /**
     * 注册流程
     *
     * @param array $params
     * @return array|bool
     */
    public function register(array $params)
    {
        $userInstance = User::getInstance();
        if (
            $userInstance->getUserByName($params['user_name'])
            ||
            $userInstance->getUserByEmail($params['user_name'])
            ||
            $userInstance->getUserByMobile($params['user_name'])
        ){
            $this->setError('该账户已被注册！');
            return false;
        }
        DB::beginTransaction();
        try {
            // 会员账户
            $user = $userInstance->create([
                'user_name' => $params['user_name'],
                'password' => $params['password'],
            ]);

            // 会员基本信息
            $ip_agent = get_client_info();
            $user->userInfo()->create([
                'user_id' => $user->user_id,
                'user_uuid' => UserInfo::getUniqueUuid(),
                'nick_name' => $params['nick_name'] ?? $user->user_name,
                'user_avatar' => cnpscy_config('site_web_logo'),
                'user_grade' => 1, // 会员等级
                'last_actived_time' => time(), // 上一次在线时间
                'created_ip'   => $ip_agent['ip'] ?? get_ip(),
                'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
            ]);

            // 第三方登录相关
            $user->userOtherlogin()->create([
                'user_id' => $user->user_id,
            ]);

            DB::commit();

            $auth = Auth::guard($this->guard);
            $auth->setUser($user);

            $this->setError('注册成功！');
            return $this->respondWithToken($auth->login($user));
        }catch (FailException $exception){
            DB::rollBack();
            $this->setError($exception->getMessage());
            return false;
        }
    }

    /**
     * 登录流程
     *
     * @param $params
     * @return array
     * @throws AuthException
     */
    public function login($params): array
    {
        $auth = Auth::guard($this->guard);
        $userInstance = User::getInstance();

        $auth_success = false;
        if ($user_name = $userInstance->getUserByName($params['user_name'])){
            if (hash_verify($params['password'], $user_name->password)){
                $auth_success = true;
                $auth->setUser($user_name);
            }
        }
        if (
            $auth_success == false
            &&
            $user_email = $userInstance->getUserByEmail($params['user_name'])
        )
        {
            if (hash_verify($params['password'], $user_email->password)){
                $auth_success = true;
                $auth->setUser($user_email);
            }
        }
        if (
            $auth_success == false
            &&
            $user_mobile = $userInstance->getUserByMobile($params['user_name'])
        )
        {
            if (hash_verify($params['password'], $user_mobile->password)){
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
        // 加载粉丝与关注人数统计
        $user->userInfo->loadCount([
            'fans',
            'follows'
        ]);
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
    protected function respondWithToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_time'   => time() + Auth::guard($this->guard)->factory()->getTTL() * 60
        ];
    }
}
