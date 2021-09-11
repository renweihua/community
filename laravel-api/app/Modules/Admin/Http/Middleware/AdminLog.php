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
            \App\Models\Log\AdminLog::addRecord(!empty(auth($guard)->user()) ? auth($guard)->user()->admin_id : 0);
        }

        return $resource;
    }
}
