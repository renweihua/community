<?php

namespace App\Modules\Bbs\Http\Middleware;

use App\Exceptions\InvalidRequestException;
use Closure;
use Illuminate\Http\Request;

/**
 * Class RestrictIPAccess
 * 仅限中国IP访问
 *
 * @package App\Modules\Bbs\Http\Middleware
 */
class RestrictIPAccess
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
        //$ip = $request->getClientIp();
        //$result = geoip()->getLocation($ip);
        //// 1.通过 torann/geoip 包，验证是否为国内IP访问：cache不可设为file、database，默认设置为：array，那么其它地方的所有cache缓存将无法生效了。【自行选择】
        //if ($ip != '127.0.0.1' && $result && $result->country != 'China'){
        //throw new InvalidRequestException('China IP access only！', 404);
        //}

        // 2.通过浏览器的语言来检测
        if( strpos(strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]),'zh-cn') !== false ){

        }else{
            throw new InvalidRequestException('China IP access only！', 404);
        }

        return $next($request);
    }
}
