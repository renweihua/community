<?php

namespace App\Modules\Bbs\Services;

use App\Constants\UserCacheKeys;
use App\Services\Service;
use App\Services\UserAuthEncryptionService;
use Illuminate\Support\Facades\Redis;

/**
 * 会员登录Token管理与数量限制
 *
 * Class UserLoginRedisService
 *
 * @package App\Modules\Bbs\Services
 */
class UserLoginRedisService extends Service
{
    // 登录Token的总数量
    const LOGIN_MAX = 3;

    protected $redis;

    public function __construct()
    {
        $this->redis = Redis::connection('token')->client();
    }

    /**
     * 获取登录会员的Token
     *
     * @param  int  $user_id
     * @param  int  $expires_time
     *
     * @return string
     */
    public static function getUserToken(int $user_id, &$expires_time = 0) : string
    {
        $expires_time = time() + UserCacheKeys::KEY_DEFAULT_TIMEOUT;
        $cache = [
            'user_id'      => $user_id,
            'expires_time' => $expires_time,
        ];
        return UserAuthEncryptionService::getInstance()->encryption($cache);
    }

    public static function decryptUserToken(string $token)
    {
        return UserAuthEncryptionService::getInstance()->decrypt($token);
    }

    /**
     * 保存登录会员的Token
     *
     * @param  array   $user_info
     * @param  string  $token
     */
    public function saveUserToken(array $user_info, string $token) : void
    {
        // Token记录在Redis，随时可控性
        /**
         * 过期时长 + 20分钟：
         *  1.10分钟内将要过期的，自动更新过期时间
         *  2.过期15分钟以内的，生成新的Token并返回
         */
        $this->redis->set(UserCacheKeys::USER_LOGIN_TOKEN . $token, my_json_encode($user_info), UserCacheKeys::KEY_DEFAULT_TIMEOUT + 20 * 60);

        // 记录token到会员Token列表中
        $this->redis->rPush(UserCacheKeys::USER_LOGIN_TOKEN_LIST . $user_info['user_id'], $token);

        // 进行登录的T欧肯数量检测
        $this->checkMax($user_info['user_id']);
    }

    // 删除指定Token
    public function deleteUserToken(string $token): void
    {
        $this->redis->del(UserCacheKeys::USER_LOGIN_TOKEN . $token);
        /**
         * 删除 会员的token列表中的键
         */
        $user = $this->decryptUserToken($token);
        if ($user){
            // 已失效的Token，删除会员下发记录的Token
            $list_user_token = UserCacheKeys::USER_LOGIN_TOKEN_LIST . $user->user_id;
            // 已失效的Token，删除会员下发记录的Token
            $this->redis->lRem($list_user_token, $token);
        }
    }

    /**
     * 对登录会员的token数量进行检测并移除
     *
     * @param $user_id
     */
    public function checkMax($user_id) : void
    {
        $list_user_token = UserCacheKeys::USER_LOGIN_TOKEN_LIST . $user_id;
        // 如果登录的Token数量超过限制，那么开始移除流程
        if ( $this->redis->lLen($list_user_token) > self::LOGIN_MAX ) {
            $list = $this->redis->lRange($list_user_token, 0, 10);
            $sortList = [];
            $user_token_prefix = UserCacheKeys::USER_LOGIN_TOKEN;
            foreach ($list as $val) {
                if ( $temp = json_decode($this->redis->get($user_token_prefix . $val)) ) {
                    $sortList[$temp->login_time] = $val;
                } else {
                    // 已失效的Token，删除会员下发记录的Token
                    $this->redis->lRem($list_user_token, $val);
                }
            }
            ksort($sortList);
            $del_nums = count($sortList) - self::LOGIN_MAX;
            for ($i = 0; $i < $del_nums; $i++) {
                $temp = array_shift($sortList);
                // 删除登录会员下方的token记录
                $this->redis->lRem($list_user_token, $temp);
                // 删除Token标识
                $this->redis->del($user_token_prefix . $temp);
            }
        }
    }
}
