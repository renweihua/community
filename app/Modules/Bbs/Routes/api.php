<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;
use App\Modules\Bbs\Http\Middleware\CheckAuth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/bbs', function (Request $request) {
    return $request->user();
});


Route::prefix('')->middleware([])->group(function () {
    // Auth
    Route::prefix('auth')->group(function () {
        // 注册
        Route::post('register', 'AuthController@register');
        // 登录
        Route::post('login', 'AuthController@login');
        // 登录会员信息
        Route::post('me', 'AuthController@me')->middleware(CheckAuth::class);
        // 退出登录
        Route::post('logout', 'AuthController@logout');
    });
    // 动态相关
    Route::prefix('dynamic')->group(function () {
        // 动态详情
        Route::get('/detail', 'DynamicController@detail');
        // 点赞 - 动态
        Route::post('/praise', 'DynamicController@praise')->middleware(CheckAuth::class);
    });
});
