<?php

namespace App\Modules\Bbs\Http\Middleware;

use App\Models\Log\WebLog;
use App\Modules\Bbs\Jobs\AsyncWebRecordLog;
use App\Traits\Json;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

            $user_id = request()->attributes->get('login_user')->user_id ?? 0;

            $time = time();
            $web_log_data = [
                'user_id'   => $user_id,
                'request_data' => json_encode($request->all()),
                'created_ip'   => $ip_agent['ip'] ?? get_ip(),
                'browser_type' => $ip_agent['agent'] ?? $_SERVER['HTTP_USER_AGENT'],
                'created_time' => $time,
                'updated_time' => $time,
                'log_action'   => request()->route()->getActionName(),
                'log_method'   => request()->getMethod(),
                'log_duration' => microtime(true) - LARAVEL_START,
                'request_url'  => URL::previous() ?? get_request_url(),
                'this_url'     => URL::full() ?? get_this_url(),
                // 默认值
                'log_status'   => 0,
                'log_description'   => '异常中断',
            ];

            $log_status = 0;
            $log_description = $web_log_data['log_description'];
            try{
                $response = $next($request);
                // 获取返回data内容，偶尔会遇到 getData 方法不存在
                if (method_exists($response, 'getData')){
                    $response_body_content = $response->getData() ?? [];
                    // 根据接口响应，存储返回状态与文本提示语
                    $log_status = $response_body_content->status == 1 ? 1 : 0;
                    $log_description = $response_body_content->msg;
                }else{
                    $log_description = 'Method Illuminate\Http\Response::getData does not exist.';
                }
            }catch(\Exception $e){
                Log::debug($log_description);
                $log_description = $e->getMessage();
                $response = $this->errorJson($log_description);
            }

            // 同步更新响应状态与文本，在`handler`层可能会被异常终止
            $web_log_data['log_duration'] = microtime(true) - LARAVEL_START;
            // 根据接口响应，存储返回状态与文本提示语
            $web_log_data['log_status'] = $log_status;
            $web_log_data['log_description'] = $log_description;

            AsyncWebRecordLog::dispatch($web_log_data)
                ->onConnection('database'); // job 存储的服务：当前存储mysql

            return $response;
        }else{
            return $next($request);
        }
    }
}
