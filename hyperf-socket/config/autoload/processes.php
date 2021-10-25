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
    /**
     * Redis异步队列
     *
     *
     * ReloadChannelListener：
     *  当消息执行超时，或项目重启导致消息执行被中断，最终都会被移动到 timeout 队列中，只要您可以保证消息执行的原子性（同一个消息执行一次，或执行多次，最终表现一致）， 就可以开启以下监听器，框架会自动将 timeout 队列中消息移动到 waiting 队列中，等待下次消费。
     *
     * 监听器监听 QueueLength 事件，默认执行 500 次消息后触发一次。
     */
    // Hyperf\AsyncQueue\Listener\ReloadChannelListener::class,
];
