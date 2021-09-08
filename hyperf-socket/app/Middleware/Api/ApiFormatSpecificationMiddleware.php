<?php

declare(strict_types=1);

namespace App\Middleware\Api;

use App\Exception\Exception;
use App\Model\App;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ApiFormatSpecificationMiddleware
 *
 * API格式规范验证中间件
 *
 * @package App\Middleware\Api
 */
class ApiFormatSpecificationMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 获取形参
        $params = $request->getQueryParams();
        if (!isset($params['app_key']) || empty($params['app_key'])){
            throw new Exception('请设置AppKey！');
        }
        if (!isset($params['app_type'])){
            throw new Exception('请设置API请求来源！');
        }
        if (!isset($params['timestamp']) || empty($params['timestamp'])){
            throw new Exception('请设置API请求的时间戳！');
        }
        // 请求时间戳超过10分钟，则废弃请求：测试期间设为1小时，便于调试
        if ($params['timestamp'] + 3600 < time()){
            throw new Exception('API请求已失效！');
        }
        if (!isset($params['nonce']) || empty($params['nonce'])){
            throw new Exception('请设置API请求随机数！');
        }

        // 获取App的秘钥
        $app_secret = App::query()->where('app_key', $params['app_key'])->value('app_secret');
        if (!$app_secret){
            throw new Exception('无效App！');
        }

        if (!isset($params['sign']) || empty($params['sign'])){
            throw new Exception('请设置API请求的签名！');
        }

        $sign = make_signature($params, $app_secret);
        // 验证签名
        if ($params['sign'] != $sign){
            throw new Exception('无效签名！');
        }

        return $handler->handle($request);
    }
}
