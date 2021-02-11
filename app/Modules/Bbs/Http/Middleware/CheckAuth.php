<?php

namespace App\Modules\Bbs\Http\Middleware;

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
        // var_dump($request->header('Authorization'));
        $this->guard = 'user';
        // Auth认证
        $auth = Auth()->guard($this->guard);
        try {
            if ( !$auth->check() ) { //未登录踢回，给予错误返回提示
                return $this->errorJson('认证失败，请重新登录！', -1);
            }
        } catch (TokenExpiredException $e) {
            return $this->errorJson($e->getMessage());
        } catch (TokenInvalidException $e) {
            return $this->errorJson($e->getMessage());
        } catch (JWTException $e) {
            return $this->errorJson($e->getMessage());
        }

        return $next($request);
    }
}
