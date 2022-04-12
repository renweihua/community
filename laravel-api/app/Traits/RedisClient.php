<?php

declare(strict_types = 1);

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait RedisClient
{
    protected static $redis;

    protected static function getRedis()
    {
        if (!self::$redis){
            self::$redis = Redis::connection()->client();
        }
        return self::$redis;
    }
}
