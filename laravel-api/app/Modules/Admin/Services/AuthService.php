<?php

namespace App\Modules\Admin\Services;

use App\Exceptions\Admin\AuthException;
use App\Exceptions\Admin\AuthTokenException;
use App\Exceptions\InvalidRequestException;
use App\Models\Log\AdminLoginLog;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class AuthService extends Service
{
    protected $guard = 'admin';

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
        $token = $auth->attempt($data);
        if (!$token) throw new AuthException('认证失败！');
        $admin = $auth->user();
        if ( !$admin ) throw new AuthException('管理员账户不存在！');
        switch ($admin->is_check) {
            case 0:
                throw new AuthException('该管理员尚未启用！', 0, $admin->admin_id);
                break;
            case 2:
                throw new AuthException('该管理员已禁用！', 0, $admin->admin_id);
                break;
        }
        AdminLoginLog::getInstance()->add($admin->admin_id, 1, '登录成功');
        return $this->respondWithToken($token);
    }

    /**
     * 登录管理员信息获取
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws \App\Exceptions\Admin\AuthTokenException
     */
    public function me()
    {
        if (!$admin = Auth::guard($this->guard)->user()){
            throw new AuthTokenException('认证失败！');
        }
        $admin->admin_head = asset($admin->admin_head);
        $admin['roles'] = ['admin'];
        return $admin;
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
            'token_type'   => 'Bearer',
            'expires_time'   => time() + Auth::guard($this->guard)->factory()->getTTL() * 60
        ];
    }
}
