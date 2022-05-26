<?php

namespace App\Modules\Bbs\Http\Middleware;

use App\Helper\RedisRateLimit;
use App\Traits\Json;
use Closure;
use Illuminate\Http\Request;

class ClientThrottle
{
    use Json;

    protected $routeWhiteList = [];
    protected $ipCount = 15;
    protected $userCount = 15;
    protected $minute = 1;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 路由
        $route = $request->url();

        // 会员Id
        $user_id = request()->attributes->get('login_user')->user_id ?? 0;
        if ($user_id) {
            if ($this->userThrottle('throttle:route:' . $route . ':user_id:' . $user_id, $this->userCount)){
                return $this->errorJson('[1]访问频次超出限制，请稍后再来！');
            }
        }else{
            // IP
            $ip = $request->getClientIp();
            if ($this->ipThrottle('throttle:route:' . $route . ':ip:' . $ip, $this->ipCount)) {
                return $this->errorJson('[2]访问频次超出限制，请稍后再来！');
            }
        }

        return $next($request);
    }

    private function userThrottle(string $key, int $max)
    {
        return RedisRateLimit::throttle($key, $this->minute, $max);
    }

    private function ipThrottle(string $key, int $max)
    {
        return RedisRateLimit::throttle($key, $this->minute, $max);
    }
}
