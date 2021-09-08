<?php

declare(strict_types=1);

namespace App\Middleware\Api;

use App\Exception\Exception;
use App\Library\Encrypt\Aes;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class VisitApiTokenMiddleware
 *
 * 访问API的Token验证中间件
 *
 * @package App\Middleware\Api
 */
class VisitApiTokenMiddleware implements MiddlewareInterface
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
        // 根据具体业务判断逻辑走向，这里假设用户携带的token有效
        $token = $request->getHeader('api-token')[0] ?? '';
        if ( strlen($token) <= 0 ) {
            throw new Exception('无权访问[01]！');
        }

        $aes = new Aes;
        $data = $aes->decrypt($token);
        if (!$data){
            throw new Exception('无权访问[02]！');
        }
        // 检测Token的过期时间
        if ($data['expire_time'] < time()){
            throw new Exception('访问无权已失效[02]！');
        }

        // 获取形参
        $params = $request->getQueryParams();
        if (!isset($params['app_key']) || empty($params['app_key'])){
            throw new Exception('请设置AppKey！');
        }
        // 验证app_key是否匹配
        if ($params['app_key'] != $data['app_key']){
            throw new Exception('无效AppKey！');
        }

        return $handler->handle($request);
    }
}
