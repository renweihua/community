<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
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
        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Credentials', 'true');
        $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, Accept, Access-Token, Authorization X-Requested-With, Content-Type, Access-Control-Allow-Headers, x-xsrf-token, x-file-name, x-frame-options, Authorization');
        // headers 白名单, 客户端可以获取到的属性
        $response->header('Access-Control-Expose-Headers', 'Authorization');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, DELETE, OPTIONS');

        return $response;
    }
}
