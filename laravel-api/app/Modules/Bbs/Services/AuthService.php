<?php

namespace App\Modules\Bbs\Services;

use App\Constants\CacheKeys;
use App\Constants\UserCacheKeys;
use App\Exceptions\Bbs\AuthException;
use App\Exceptions\Bbs\AuthTokenException;
use App\Exceptions\Bbs\FailException;
use App\Library\Encrypt\Rsa;
use App\Models\Log\UserLoginLog;
use App\Models\System\Notify;
use App\Models\User\User;
use App\Models\User\UserInfo;
use App\Modules\Bbs\Jobs\SendRegisterEmail;
use App\Services\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class AuthService extends Service
{
    protected $redis;

    public function __construct()
    {
        $this->redis = Redis::connection('token');
    }

    /**
     * 注册流程
     *
     * @param array $params
     * @return array|bool
     */
    public function register(array $params)
    {
        $userInstance = User::getInstance();
        $user_info['register_type'] = (int)$params['register_type'];
        switch ((int)$user_info['register_type']){
            case 0: // 用户名注册
                if ($userInstance->getUserByName($params['user_name'])){
                    $this->setError('该账户已被注册！');
                    return false;
                }
                $user_data['user_name'] = $params['user_name'];
                break;
            case 1: // 邮箱注册
                if (!is_email($params['user_name'])){
                    $this->setError('请输入有效的邮箱地址！');
                    return false;
                }
                if ($userInstance->getUserByEmail($params['user_name'])){
                    $this->setError('该邮箱已被注册！');
                    return false;
                }
                $user_data['user_email'] = $params['user_name'];
                break;
            case 2: // 手机号注册
                if (!is_mobile($params['user_name'])){
                    $this->setError('请输入有效的手机号！');
                    return false;
                }
                if ($userInstance->getUserByMobile($params['user_name'])){
                    $this->setError('该手机号已被注册！');
                    return false;
                }
                $user_data['user_mobile'] = $params['user_name'];
                break;
            default:
                $this->setError('无效注册！');
                return false;
                break;
        }
        DB::beginTransaction();
        try {
            $user_data['password'] = $params['password'];
            // 会员账户
            $user = $userInstance->create($user_data);

            // 会员基本信息
            $ip_agent = get_client_info();
            $user_info = array_merge($user_info, [
                'user_id' => $user->user_id,
                'user_uuid' => UserInfo::getUniqueUuid(),
                'user_avatar' => Storage::url(cnpscy_config('site_web_logo')),
                'user_sex' => 0,
                'user_grade' => 1, // 会员等级
                'last_actived_time' => time(), // 上一次在线时间
                'created_ip'   => $ip_agent['ip'] ?? get_ip(),
                'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
            ]);
            $user->userInfo()->create($user_info);

            // 第三方登录相关
            $user->userOtherlogin()->create([
                'user_id' => $user->user_id,
            ]);

            DB::commit();

            // 注册成功：站内系统消息发送
            Notify::insert([
                'notify_type' => Notify::NOTIFY_TYPE['SYSTEM_MSG'],
                'user_id' => $user->user_id,
                'target_type' => Notify::TARGET_TYPE['REGISTER'],
                'sender_type' => Notify::SYSTEM_SENDER,
                'notify_content' => '注册成功，请完善个人资料！',
            ]);

            if ($user_info['register_type'] == 1){
                // 注册成功：发送邮件通知，追加到
                SendRegisterEmail::dispatch($user_data['user_email'])
                    ->delay(now()->addMinutes(10)) // 延迟10分钟
                    ->onConnection('database') // job 存储的服务：当前存储mysql
                    ->onQueue('mail-queue'); // mail-queue 队列
            }

            $this->setError('注册成功，请完善个人资料！');
            $result = $this->respondWithToken($user->user_id);

            // Token记录在Redis，随时可控性
            $this->redis->set(UserCacheKeys::USER_LOGIN_TOKEN . $result['access_token'], my_json_encode(['user_id' => $user->user_id, 'expires_time' => $result['expires_time']]), UserCacheKeys::KEY_DEFAULT_TIMEOUT);

            return array_merge($result, [
                'user_avatar' => $user_info['user_avatar'],
                'user_sex' => $user_info['user_sex'],
            ]);
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
        $userInstance = User::getInstance();

        $auth_success = false;
        if ($user_name = $userInstance->getUserByName($params['user_name'])){
            if (hash_verify($params['password'], $user_name->password)){
                $auth_success = true;
                $user = $user_name;
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
                $user = $user_email;
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
                $user = $user_mobile;
            }
        }
        // 如果账户、邮箱、手机号，都验证失败 ，那么登录失败
        if (!$auth_success){
            throw new AuthException('认证失败！');
        }
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

        $result = $this->respondWithToken($user->user_id);

        // Token记录在Redis，随时可控性
        $this->redis->set(UserCacheKeys::USER_LOGIN_TOKEN . $result['access_token'], my_json_encode(['user_id' => $user->user_id, 'expires_time' => $result['expires_time']]), UserCacheKeys::KEY_DEFAULT_TIMEOUT);

        return $result;
    }

    /**
     * 登录管理员信息获取
     *
     * @param $request
     *
     * @return mixed
     * @throws \App\Exceptions\Bbs\AuthTokenException
     */
    public function me($request)
    {
        if (!$user = $request->attributes->get('login_user')){
            throw new AuthTokenException('认证失败！');
        }
        // 加载粉丝与关注人数统计
        $user->userInfo->loadCount([
            'fans',
            'follows'
        ]);
        // 是否已签到
        $user->userInfo->is_sign = false;
        if ($user->userInfo->last_sign_time > 0 && date('Y-m-d') == date('Y-m-d', $user->userInfo->last_sign_time)){
            $user->userInfo->is_sign = true;
        }
        return $user;
    }

    /**
     * 退出登录
     *
     * @return bool
     */
    public function logout($token)
    {
        $this->redis->del(UserCacheKeys::USER_LOGIN_TOKEN . $token);
        return true;
    }

    /**
     * Get the token array structure.
     *
     * @param $token
     * @return array
     */
    protected function respondWithToken($user_id): array
    {
        $expire_time = time() + CacheKeys::KEY_DEFAULT_TIMEOUT;
        $token = $this->getUserToken($user_id, $expire_time);
        return [
            'access_token' => $token,
            'expires_time'   => $expire_time,
        ];
    }

    /**
     * 获取登录会员的Token
     *
     * @param  int  $user_id
     *
     * @return string|null
     */
    protected function getUserToken(int $user_id, int $expire_time)
    {
        return Rsa::publicEncrypt(['user_id' => $user_id, 'expire_time' => $expire_time]);
    }
}
