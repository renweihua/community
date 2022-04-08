<?php

namespace App\Modules\Bbs\Http\Middleware;

use App\Constants\UserCacheKeys;
use App\Library\Encrypt\Rsa;
use App\Models\Log\WebLog;
use App\Traits\Json;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\URL;

class RecordWebLog
{
    use Json;

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
        $is_start = intval(cnpscy_config('start_web_logs', 0)) == 1 ? true : false;
        if ($is_start) {
            $ip_agent = get_client_info();

            $user_id = request()->attributes->get('login_user') ?? 0;

            $weblog = WebLog::create(
                [
                    'user_id'   => $user_id,
                    'request_data' => json_encode($request->all()),
                    'created_ip'   => $ip_agent['ip'] ?? get_ip(),
                    'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
                    'created_time' => time(),
                    'log_action'   => request()->route()->getActionName(),
                    'log_method'   => request()->getMethod(),
                    'log_duration' => microtime(true) - LARAVEL_START,
                    'request_url'  => URL::previous() ?? get_request_url(),
                    'this_url'     => URL::full() ?? get_this_url(),
                    // 默认值
                    'log_status'   => 0,
                    'log_description'   => '异常中断',
                ]
            );

            $log_status = 0;
            $log_description = $weblog->log_description;
            try{
                $response = $next($request);

                // 获取返回data内容
                $response_body_content = $response->getData();

                // 根据接口响应，存储返回状态与文本提示语
                $log_status = $response_body_content->status;
                $log_description = $response_body_content->msg;
            }catch(\Exception $e){
                $log_description = $e->getMessage();
                $response = $this->errorJson($log_description);
            }

            // 同步更新响应状态与文本，在`handler`层可能会被异常终止
            $weblog->update(
                [
                    'log_duration' => microtime(true) - LARAVEL_START,
                    // 根据接口响应，存储返回状态与文本提示语
                    'log_status'   => $log_status,
                    'log_description'   => $log_description,
                ]
            );

            return $response;
        }else{
            return $next($request);
        }
    }
}
