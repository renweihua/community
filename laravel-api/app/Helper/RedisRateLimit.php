<?php

namespace App\Helper;

use App\Traits\RedisClient;

/**
 * redis 通过计数器限流
 *
 * Class RedisRateLimit
 */
class RedisRateLimit
{
    use RedisClient;

    public static function throttle($key, int $minute = 1, int $max = 100)
    {
        // 获取Redis实例
        $redis = self::getRedis();
        // 毫秒时间戳
        $msectime = self::msectime();
        // 使用管道提升性能
        $pipe = $redis->multi($redis::PIPELINE);
        // vaule 与 score 使用毫秒时间戳
        $pipe->zadd($key, $msectime, $msectime);

        // 删除时间窗口之前的历史记录
        $pipe->zRemRangeByScore($key, 0, $msectime - $minute * 60 * 1000);

        // 获取当前时间窗口内所有的总数
        $pipe->zcard($key);
        // 多1秒过期时间
        $pipe->expire($key, $minute * 60 + 1);

        $replies = $pipe->exec();

        return $replies[2] > $max ? true : false;
    }

    //返回当前的毫秒时间戳
    static function msectime() {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }
}
