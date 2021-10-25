<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'default' => [
        'driver' => Hyperf\AsyncQueue\Driver\RedisDriver::class,
        'redis' => [
            'pool' => 'default', // redis 连接池
        ],
        'channel' => 'queue',
        'timeout' => 3,
        'retry_seconds' => 5,
        'handle_timeout' => 10,
        'processes' => 1,
        'concurrent' => [
            'limit' => 5, // 同时处理消息数
        ],
    ],
    'queue' => [
        'driver' => Hyperf\AsyncQueue\Driver\RedisDriver::class,
        'redis' => [
            'pool' => 'queue', // redis 连接池
        ],
        'channel' => 'queue', // 队列前缀
        'timeout' => 3, // pop 消息的超时时间
        'retry_seconds' => [10, 50, 100], // 失败后重新尝试间隔
        'handle_timeout' => 10, // 消息处理超时时间
        'processes' => 1, // 消费进程数
        'concurrent' => [
            'limit' => 5, // 同时处理消息数
        ],
    ],
    'query-list' => [
        'driver' => Hyperf\AsyncQueue\Driver\RedisDriver::class,
        'redis' => [
            'pool' => 'query-list', // redis 连接池
        ],
        'channel' => 'query_list', // 队列前缀
        'timeout' => 3, // pop 消息的超时时间
        'retry_seconds' => [10, 50, 100], // 失败后重新尝试间隔
        'handle_timeout' => 10, // 消息处理超时时间
        'processes' => 1, // 消费进程数
        'concurrent' => [
            'limit' => 5, // 同时处理消息数
        ],
    ],
];
