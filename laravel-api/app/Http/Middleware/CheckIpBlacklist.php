<?php

namespace App\Http\Middleware;

use App\Traits\Json;
use Closure;
use Illuminate\Http\Request;

class CheckIpBlacklist
{
    use Json;

    protected $guard;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $client_ip = $request->getClientIp();
        if (!$client_ip){
            $client_ip = get_ip();
        }

        // 获取黑名单组
        $ip_blacklists = cnpscy_config('ip_blacklists');
        if ($ip_blacklists){
            $ip_blacklists_array = explode(',', $ip_blacklists);
            // 键值翻转，检测是否存在数组key
            $ip_blacklists_array = array_flip($ip_blacklists_array);
            if (isset($ip_blacklists_array[$client_ip])){
                $msg = '您的IP段在系统黑名单中，禁止访问！';
                abort(403, $msg);
            }
        }

        // IP段访问限制（上海腾讯云的IP）
        if (check_ip_range($ip, '175.24.211.1', '175.24.214.255')){
            $msg = '您的IP段已被禁止访问！';
            if ($request->expectsJson()) {
                return $this->errorJson($msg);
            }else{
                abort(403, $msg);
            }
        }


        return $next($request);
    }
}
