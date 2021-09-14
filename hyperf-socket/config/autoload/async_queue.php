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
            'pool' => 'default', // redis ���ӳ�
        ],
        'channel' => 'queue',
        'timeout' => 3,
        'retry_seconds' => 5,
        'handle_timeout' => 10,
        'processes' => 1,
        'concurrent' => [
            'limit' => 5, // ͬʱ������Ϣ��
        ],
    ],
    'queue' => [
        'driver' => Hyperf\AsyncQueue\Driver\RedisDriver::class,
        'redis' => [
            'pool' => 'queue', // redis ���ӳ�
        ],
        'channel' => 'queue', // ����ǰ׺
        'timeout' => 3, // pop ��Ϣ�ĳ�ʱʱ��
        'retry_seconds' => [10, 50, 100], // ʧ�ܺ����³��Լ��
        'handle_timeout' => 10, // ��Ϣ����ʱʱ��
        'processes' => 1, // ���ѽ�����
        'concurrent' => [
            'limit' => 5, // ͬʱ������Ϣ��
        ],
    ],
    'query-list' => [
        'driver' => Hyperf\AsyncQueue\Driver\RedisDriver::class,
        'redis' => [
            'pool' => 'query-list', // redis ���ӳ�
        ],
        'channel' => 'query_list', // ����ǰ׺
        'timeout' => 3, // pop ��Ϣ�ĳ�ʱʱ��
        'retry_seconds' => [10, 50, 100], // ʧ�ܺ����³��Լ��
        'handle_timeout' => 10, // ��Ϣ����ʱʱ��
        'processes' => 1, // ���ѽ�����
        'concurrent' => [
            'limit' => 5, // ͬʱ������Ϣ��
        ],
    ],
];
