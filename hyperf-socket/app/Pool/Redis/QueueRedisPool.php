<?php
/**
 * Created by IntelliJ IDEA.
 * User: pc
 * Date: 2020/9/25
 * Time: 9:49
 * Note: RedisPool.php
 */

namespace App\Pool\Redis;

class QueueRedisPool extends RedisPool
{
    /**
     * @var string
     */
    protected $poolName = 'queue';
}