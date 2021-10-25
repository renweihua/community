<?php

declare(strict_types=1);

namespace App\Middleware\Bbs;

use App\Library\Encrypt\Rsa;
use App\Middleware\ExecutionMiddleware;
use App\Model\Log\WebLog;
use App\Utils\ExecutionTime;
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
class RecordWebLog implements MiddlewareInterface
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
        /**
         * 记录Web日志
         */
        if (intval(cnpscy_config('start_web_logs', 0)) == 1) {
            $ip_agent = get_client_info();

            $user_id = 0;
            if($token = $request->getHeader('Authorization')){
                $token_user = Rsa::privDecrypt($token);
                if ($token_user){
                    $user_id = $token_user->user_id ?? 0;
                }
            }

            WebLog::create(
                [
                    'user_id'   => $user_id,
                    'request_data' => json_encode($request->all()),
                    'created_ip'   => $ip_agent['ip'] ?? get_ip(),
                    'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
                    'created_time' => time(),
                    'log_action'   => $request->getUri(),
                    'log_method'   => $request->getMethod(),
                    'log_duration' => microtime(true) - ExecutionTime::$start_time,
                    'request_url'  => get_request_url(),
                    'this_url'     => get_this_url(),
                ]
            );
        }

        return $handler->handle($request);
    }
}
