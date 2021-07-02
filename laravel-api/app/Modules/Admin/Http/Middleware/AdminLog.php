<?php

namespace App\Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AdminLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $guard = 'admin';

        $resource = $next($request);

        $method = strtoupper($request->getMethod());

        if ($method != 'GET'){
            $ip_agent = get_client_info();
            \App\Models\Log\AdminLog::getInstance()->create([
                'request_data' => json_encode($request->all()),
                'admin_id'     => !empty(auth($guard)->user()) ? auth($guard)->user()->admin_id : 0,
                'created_ip'   => $ip_agent['ip'] ?? get_ip(),
                'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
                'created_time' => time(),
                'log_action'   => request()->route()->getActionName(),
                'log_method'   => $method,
                'log_duration' => microtime(true) - LARAVEL_START,
                'request_url'     => URL::full() ?? get_this_url(),
            ]);
        }

        return $resource;
    }
}
