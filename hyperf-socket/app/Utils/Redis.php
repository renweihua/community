<?php

namespace App\Utils;

use Hyperf\Redis\RedisFactory;

class Redis
{
    public static function getInstance(string $name = 'default')
    {
        return di()->get(RedisFactory::class)->get($name);
    }
}