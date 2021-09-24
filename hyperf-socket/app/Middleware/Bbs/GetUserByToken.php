<?php

namespace App\Middleware\Bbs;

use App\Library\Encrypt\Rsa;
use App\Model\User\User;
use Hyperf\Utils\Context;
use App\Traits\Json;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetUserByToken implements MiddlewareInterface
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
        $token = $request->getHeader('Authorization');
        if (!empty($token)){
            if ($token_user = Rsa::privDecrypt($token)){
                // Redis 是否存在key
                // if (!empty(Redis::connection('token')->get(UserCacheKeys::USER_LOGIN_TOKEN . $token))){
                    // Token 是否过期
                    if (isset($token_user->expires_time) && $token_user->expires_time > time()){
                        $user = User::find($token_user->user_id);
                        if ($user->is_check == 1){
                            /**
                             * 把登录会员信息追加到 request类
                             *
                             * 协程上下文：https://hyperf.wiki/2.0/#/zh-cn/coroutine?id=%e5%8d%8f%e7%a8%8b%e4%b8%8a%e4%b8%8b%e6%96%87
                             *
                             * 避免协程间数据混淆：https://hyperf.wiki/2.0/#/zh-cn/controller?id=%e9%81%bf%e5%85%8d%e5%8d%8f%e7%a8%8b%e9%97%b4%e6%95%b0%e6%8d%ae%e6%b7%b7%e6%b7%86
                             */
                            $request = Context::get(ServerRequestInterface::class);
                            $request = $request->withAttribute('login_user', $user);
                            Context::set(ServerRequestInterface::class, $request);
                        }
                    }
                // }
            }
        }
        return $handler->handle($request);
    }
}
