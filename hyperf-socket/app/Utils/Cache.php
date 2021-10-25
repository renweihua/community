<?php

namespace App\Utils;

use Hyperf\Utils\ApplicationContext;
use Psr\SimpleCache\CacheInterface;

class Cache
{
    public static function getInstance()
    {
        return ApplicationContext::getContainer()->get(CacheInterface::class);
    }

    public static function __callStatic($name, $arguments)
    {
        return self::getInstance()->$name(...$arguments);
    }

    public static function remember($key, $ttl = null, $callback)
    {
        $class = self::getInstance();
        if ($class->has($key)){
            return $class->get($key);
        }

        // 执行闭包
        if ($callback instanceof \Closure){
            $value = $callback();
        }else{
            $value = $callback;
        }

        $class->set($key, $value, $ttl);

        return $value;
    }
}