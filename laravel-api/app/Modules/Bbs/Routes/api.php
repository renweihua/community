<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;
use App\Modules\Bbs\Http\Middleware\CheckAuth;
use App\Modules\Bbs\Http\Middleware\GetUserByToken;

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


Route::prefix('')->middleware(\App\Http\Middleware\Cors::class)->group(function () {
    // Auth
    Route::prefix('auth')->group(function () {
        // 邮箱注册，发送验证码
        Route::get('getCodeByEmail', 'AuthController@getCodeByEmail');
        // 注册
        Route::post('register', 'AuthController@register');
        // 登录
        Route::match(['get', 'post'], 'login', 'AuthController@login');
        // 登录会员信息
        Route::match(['get', 'post'], 'me', 'AuthController@me')->middleware(CheckAuth::class);
        // 退出登录
        Route::post('logout', 'AuthController@logout');
    });

    Route::prefix('')->middleware(GetUserByToken::class)->group(function(){
        /**
         * 首页相关
         */
        Route::prefix('/')->group(function () {
            // 发现
            Route::get('discover', 'IndexController@discover');
            // 关注
            Route::get('follow', 'IndexController@follows')->middleware(CheckAuth::class);
        });

        /**
         * 会员相关
         */
        Route::get('users', 'UserController@lists');
        // 指定会员详情
        Route::prefix('user')->group(function () {
            // 检测会员账户是否已被注册
            Route::post('/exists', 'UserController@exists');
            // 指定会员详情
            Route::get('/detail', 'UserController@detail');
            // 指定会员的动态
            Route::get('/dynamics', 'UserController@dynamics');
        });

        /**
         * 动态相关
         */
        Route::get('dynamics', 'DynamicController@lists');
        Route::prefix('dynamic')->group(function () {
            // 动态详情
            Route::get('/detail', 'DynamicController@detail');
            // 获取动态评论列表
            Route::get('/comments', 'DynamicController@comments');
            // 加载指定评论，更多的回复列表
            Route::get('/loadMoreComments', 'DynamicController@loadMoreComments');
            // 指定动态：点赞人员记录
            Route::get('/getPraises', 'DynamicController@getPraises');
        });

        /**
         * 荟吧相关
         */
        // 荟吧列表
        Route::get('topics', 'TopicController@lists');
        Route::prefix('topic')->group(function () {
            // 荟吧详情
            Route::get('/detail', 'TopicController@detail');
            // 荟吧的动态列表
            Route::get('/dynamics', 'TopicController@dynamics');
            // 关注指定荟吧
            Route::post('/follow', 'TopicController@follow')->middleware(CheckAuth::class);
        });
    });

    // 登录会员
    Route::prefix('')->middleware(CheckAuth::class)->namespace('User')->group(function () {
        // 文件上传
        Route::post('upload_file', 'UploadController@file');
        // 批量文件上传
        Route::post('upload_files', 'UploadController@files');

        /**
         * 好友相关
         */
        Route::prefix('user')->group(function () {
            // 我关注的会员
            Route::get('/follows', 'FriendController@follows');
            // 我关注的荟吧
            Route::get('/follow_topics', 'TopicController@follows');
            // 关注指定会员
            Route::post('/follow', 'FriendController@follow');
            // 指定会员，设置是否特别关注
            Route::put('/setSpecial', 'FriendController@setSpecial');
            // 指定会员，设置是否拉黑
            Route::put('/setBlacklist', 'FriendController@setBlacklist');
            // 我的粉丝
            Route::get('/fans', 'FriendController@fans');


            // 我的收藏
            Route::get('/collections', 'DynamicController@collections');
            // 今日签到信息
            Route::get('/getSignByToday', 'SignController@getSignByToday');
            // 每日签到
            Route::post('/signIn', 'SignController@signIn');
            // 指定月份的签到状态
            Route::get('/getSignsStatusByMonth', 'SignController@getSignsStatusByMonth');
            // 我的签到记录：按月筛选
            Route::get('/getSignsByMonth', 'SignController@getSignsByMonth');
            // 编辑个人资料
            Route::put('/update', 'IndexController@update');
            // 更换背景封面图
            Route::put('/updateBackgroundCover', 'IndexController@updateBackgroundCover');
            // 更改登录密码
            Route::put('/changePassword', 'IndexController@changePassword');
            // 更改登录密码时，通过邮箱：发送验证码
            Route::get('/sendMailByChangePassword', 'IndexController@sendMailByChangePassword')->withoutMiddleware(CheckAuth::class);
            // 通过邮箱更改登录密码
            Route::put('/changePassByEmail', 'IndexController@changePassByEmail');


            // 指定会员是否在黑名单：前期先测试数据返回
            Route::get('/getUserBlackExists', 'TestController@getUserBlackExists');
        });

        /**
         * 动态相关
         */
        Route::prefix('dynamic')->group(function () {
            // 发布 - 动态
            Route::post('/push', 'DynamicController@push');
            // 点赞 - 动态
            Route::post('/praise', 'DynamicController@praise');
            // 收藏 - 动态
            Route::post('/collection', 'DynamicController@collection');
            // 评论 - 动态
            Route::post('/comment', 'DynamicController@comment');
            // 删除评论 - 动态
            Route::delete('/delete/comment', 'DynamicController@deleteComment');
        });

        /**
         * 消息相关
         */
        Route::prefix('')->group(function () {
            // 我的未读消息
            Route::get('/notify/unread', 'NotifyController@unread');
            // 我的{提醒|系统}消息通知
            Route::get('/user/getSystemByNotify', 'NotifyController@getSystemByNotify');
            // 我的{点赞}消息通知
            Route::get('/user/getPraiseByNotify', 'NotifyController@getPraiseByNotify');
            // 我的{评论}消息通知
            Route::get('/user/getCommentByNotify', 'NotifyController@getCommentByNotify');
        });

        // 登录日志记录
        Route::get('/user/login_logs', 'LoginLogController@getListsByMonth');
    });

    // 其它系统配置
    Route::prefix('')->group(function () {
        // 首页启动图
        Route::get('start_diagrams', 'WebSitesController@getStartDiagrams');
        // 注册协议
        Route::get('register_agreement', 'WebSitesController@registerAgreement');
        // 关于我们
        Route::get('about_us', 'WebSitesController@aboutUs');
        // Banner图
        Route::get('banners', 'WebSitesController@banners');
    });
});
