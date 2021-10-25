<?php

declare (strict_types = 1);

namespace App\Model;

/**
 * Class App
 *
 * App模型
 *
 * @package App\Model
 */
class App extends Model
{
    protected $primaryKey = 'app_id';
    protected $is_delete  = 0;

    /**
     * 生成唯一的key
     *
     * @return string
     */
    public static function makeUniqueKey(): string
    {
        $key = make_uuid();
        if (self::query()->where('app_key', $key)->first()){
            return self::makeUniqueKey();
        }
        return $key;
    }

    /**
     * 生成唯一的秘钥
     *
     * @param  string  $app_key APP的key
     *
     * @return string
     */
    public static function makeUniqueSecret(string $app_key): string
    {
        $secret = make_uuid($app_key);
        if (self::query()->where('app_secret', $secret)->first()){
            return self::makeUniqueSecret($app_key);
        }
        return $secret;
    }
}