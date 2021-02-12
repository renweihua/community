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

    // 首页相关
    Route::prefix('/')->group(function () {
        // 发现
        Route::get('discover', 'IndexController@discover');
    });

    // 会员相关
    Route::prefix('user')->group(function () {
        // 指定会员详情
        Route::get('/detail', 'UserController@detail');
        // 指定会员的动态
        Route::get('/dynamics', 'UserController@dynamics');
    });

    // 动态相关
    Route::prefix('dynamic')->group(function () {
        // 动态详情
        Route::get('/detail', 'DynamicController@detail');
        // 获取动态评论列表
        Route::get('/comments', 'DynamicController@comments');
        // 加载指定评论，更多的回复列表
        Route::get('/loadMoreComments', 'DynamicController@loadMoreComments');
    });

    // 登录会员
    Route::prefix('')->middleware([CheckAuth::class])->namespace('User')->group(function () {

        // 好友相关
        // 关注指定会员
        Route::post('user/follow', 'FriendController@follow');
        Route::prefix('friend')->group(function () {
            // 我的关注
            Route::get('/follows', 'FriendController@follows');
            // 我的粉丝
            Route::get('/fans', 'FriendController@fans');
        });

        // 动态相关
        Route::prefix('dynamic')->group(function () {
            // 指定动态：点赞人员记录
            Route::get('/getPraises', 'DynamicController@getPraises');
            // 点赞 - 动态
            Route::post('/praise', 'DynamicController@praise');
            // 点赞 - 动态
            Route::post('/collection', 'DynamicController@collection');
            // 评论 - 动态
            Route::post('/comment', 'DynamicController@comment');
        });
    });

    // 其它系统配置
    Route::prefix('')->group(function () {
        // 首页启动图
        Route::get('start_diagrams', 'WebSitesController@getStartDiagrams');
    });

});
