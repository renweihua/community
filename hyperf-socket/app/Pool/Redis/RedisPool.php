<?php
/**
 * Created by IntelliJ IDEA.
 * User: pc
 * Date: 2020/9/25
 * Time: 9:49
 * Note: RedisPool.php
 */

namespace App\Pool\Redis;

use Hyperf\Redis\Redis;

class RedisPool extends Redis
{
    /**
     * @var string
     */
    protected $poolName = 'default';
}