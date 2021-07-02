<?php

namespace App\Modules\Bbs\Http\Middleware;

use App\Models\Log\WebLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class RecordWebLog
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
        /**
         * 记录Web日志
         */
        if (intval(cnpscy_config('start_web_logs', 0)) == 1) {
            $ip_agent = get_client_info();
            WebLog::create(
                [
                    'request_data' => json_encode($request->all()),
                    'created_ip'   => $ip_agent['ip'] ?? get_ip(),
                    'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
                    'created_time' => time(),
                    'log_action'   => request()->route()->getActionName(),
                    'log_method'   => request()->getMethod(),
                    'log_duration' => microtime(true) - LARAVEL_START,
                    'request_url'  => URL::previous() ?? get_request_url(),
                    'this_url'     => URL::full() ?? get_this_url(),
                ]
            );
        }

        return $next($request);
    }
}
