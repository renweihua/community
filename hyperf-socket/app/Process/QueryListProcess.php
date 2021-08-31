<?php

declare(strict_types=1);

namespace App\Process;

use App\Pool\Redis\QueryListRedisPool;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Event\AfterHandle;
use Hyperf\AsyncQueue\Event\BeforeHandle;
use Hyperf\AsyncQueue\Event\FailedHandle;
use Hyperf\AsyncQueue\Event\QueueLength;
use Hyperf\AsyncQueue\Event\RetryHandle;
use Hyperf\AsyncQueue\MessageInterface;
use Hyperf\AsyncQueue\Process\ConsumerProcess;
use Hyperf\Process\Annotation\Process;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Coroutine\Concurrent;
use Psr\Container\ContainerInterface;

/**
 * @Process(name="QueryListProcess")
 */
class QueryListProcess extends ConsumerProcess
{
    /**
     * 进程数量
     * @var int
     */
    public $nums = 1;

    /**
     * 进程名称
     * @var string
     */
    public $name = 'query-list-process';

    /**
     * @var string
     */
    protected $queue = 'query-list';

    /**
     * 是否启用协程
     * @var bool
     */
    public $enableCoroutine = true;

    // 是否跟随服务启动一同启动
    public function isEnable($server): bool
    {
        return true;
    }
}
