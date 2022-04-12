<?php

namespace App\Helper;

use App\Traits\RedisClient;

/**
 * redis分布式锁
 *
 * Class RedisLock
 */
class RedisLock
{
    use RedisClient;

    const RELEASE_SUCCESS = 1; // 释放锁的返回
    const EXPIRE_TIME = 10; // 10s

    /**
     * 获取redis锁
     *
     * @param string $key           锁
     * @param string $request_id    请求Id（保证加解锁的唯一性，避免A客户端解除B客户端的锁）
     * @param int    $expires       过期时间
     *
     * @return bool
     */
    public static function lock(string $key, string $request_id, int $expires = self::EXPIRE_TIME): bool
    {
        $redis = self::getRedis();
        // 获取redis锁
        while (true){
            // "NX" 仅在key不存在时加锁, 满足条件1: 互斥型
            // "EX" 设置锁过期时间, 满足条件2: 避免死锁
            // - EX second ：设置键的过期时间为 second 秒。 SET key value EX second 效果等同于 SETEX key second value 。
            // - PX millisecond ：设置键的过期时间为 millisecond 毫秒。 SET key value PX millisecond 效果等同于 PSETEX key millisecond value 。
            $lock = $redis->set($key, $request_id, ['nx', 'ex' => $expires]);
            if ($lock){
                break;
            }
            usleep(500);
        }
        return true;
    }

    public static function unlock(string $key, string $request_id): bool
    {
        $redis = self::getRedis();
        // 如果直接使用del，任何一个客户端都可以解锁，甚至会把别人的锁给解除了.
        // $redis->del($key);

        // 当锁拥有的客户端完成了对共享资源的操作后，释放锁需要用到 Lua 脚本
        $lua =<<<EOT
if redis.call("get",KEYS[1]) == ARGV[1] then
    return redis.call("del",KEYS[1])
else
    return 0
end
EOT;
        $result = $redis->eval($lua, array($key, $request_id), 1);
        return self::RELEASE_SUCCESS === $result;
    }
}
