<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConvertEmptyStringsToNull
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
        $method = strtoupper($request->getMethod());
        if (
            $method == 'POST'
            ||
            $method == 'PUT'
            ||
            $method == 'PATCH'
        ){
            // 希望在POST、PUT、PATCH时，新增与更新数据时，移除[空字符串自动转换为null的中间件]效果。
        }else{
            return (new \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull)->handle($request, $next);
        }

        return $next($request);
    }
}
