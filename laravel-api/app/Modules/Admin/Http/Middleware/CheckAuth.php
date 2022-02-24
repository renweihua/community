<?php

namespace App\Modules\Admin\Http\Middleware;

use App\Traits\Json;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class CheckAuth
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
        $this->guard = 'admin';
        // Auth认证
        $auth = Auth()->guard($this->guard);
        try {
            if ( !$auth->check() ) { //未登录踢回，给予错误返回提示
                return $this->errorJson('认证失败，请重新登录！', -1);
            }
        } catch (TokenExpiredException $e) {
            return $this->errorJson($e->getMessage(), -1);
        } catch (TokenInvalidException $e) {
            return $this->errorJson($e->getMessage(), -1);
        } catch (JWTException $e) {
            return $this->errorJson($e->getMessage());
        }

        return $next($request);
    }
}
