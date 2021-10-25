<?php

declare(strict_types = 1);

namespace App\Middleware;

use App\Utils\ExecutionTime;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 记录程序执行的开始时间（用于计算接口执行时长）
 *
 * Class ExecutionMiddleware
 *
 * @package App\Middleware
 */
class ExecutionMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        // 执行程序的开始时间
        ExecutionTime::setStartTime(microtime(true));

        // /**
        //  * 协程上下文：https://hyperf.wiki/2.0/#/zh-cn/coroutine?id=%e5%8d%8f%e7%a8%8b%e4%b8%8a%e4%b8%8b%e6%96%87
        //  *
        //  * 避免协程间数据混淆：https://hyperf.wiki/2.0/#/zh-cn/controller?id=%e9%81%bf%e5%85%8d%e5%8d%8f%e7%a8%8b%e9%97%b4%e6%95%b0%e6%8d%ae%e6%b7%b7%e6%b7%86
        //  */
        // $request = Context::get(ServerRequestInterface::class);
        // $request = $request->withAttribute('execution_start', microtime(true));
        // Context::set(ServerRequestInterface::class, $request);
        //
        // // 其它地方获取：$this->request->getAttribute('execution_start')

        return $handler->handle($request);
    }
}