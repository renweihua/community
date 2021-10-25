<?php

declare(strict_types = 1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

use App\Middleware\Bbs\RecordWebLog;
use App\Middleware\Bbs\CheckAuth;
use App\Middleware\Bbs\GetUserByToken;

use App\Controller\Bbs\WebSitesController;
use App\Controller\Bbs\AuthController;
use App\Controller\Bbs\IndexController;

Router::addGroup(
    '/api/',
    function() {
        // Auth
        Router::addGroup('auth/', function () {
            // 邮箱注册，发送验证码
            Router::get('getCodeByEmail', [AuthController::class, 'getCodeByEmail']);
            // 注册
            Router::post('register', [AuthController::class, 'register']);
            // 登录
            Router::addRoute(['get', 'post'], 'login', [AuthController::class, 'login']);
            // 登录会员信息
            Router::addRoute(['get', 'post'], 'me', [AuthController::class, 'me']);
            // 退出登录
            Router::post('logout', [AuthController::class, 'logout']);
        });

        Router::addGroup('', function () {
            /**
             * 首页相关
             */
            Router::addGroup('', function () {
                // 发现
                Router::get('discover', [IndexController::class, 'discover']);
                // 关注
                Router::get('follow', [IndexController::class, 'follows'], ['middleware' => CheckAuth::class]);
            });
            /**
             * 会员相关
             */
            Router::get('users', 'UserController@lists');
            // 指定会员详情
            Router::addGroup('user', function () {
                // 通过UUID获取会员详情
                Router::get('/{user_uuid}/detail', 'UserController@uuid');
                // 检测会员账户是否已被注册
                Router::post('/exists', 'UserController@exists');
                // 指定会员详情
                Router::get('/detail', 'UserController@detail');
                // 指定会员的动态
                Router::get('/dynamics', 'UserController@dynamics');
                // 邮箱激活
                Router::get('/activate/{verify_token}', 'UserController@activeEmail')->name('user.activate');
                // 更改邮箱激活
                Router::get('/activate.changeEamil/{verify_token}', 'UserController@activeChangeEmail')->name('user.activate_change_email');
                // 指定会员的粉丝
                Router::get('/{user_id}/fans', 'UserController@fans');
                // 指定会员的关注
                Router::get('/{user_id}/follows', 'UserController@follows');
            });

            /**
             * 动态相关
             */
            Router::get('dynamics', 'DynamicController@lists');
            Router::addGroup('dynamic', function () {
                // 动态详情
                Router::get('/detail', 'DynamicController@detail');
                // 获取动态评论列表
                Router::get('/comments', 'DynamicController@comments');
                // 加载指定评论，更多的回复列表
                Router::get('/loadMoreComments', 'DynamicController@loadMoreComments');
                // 指定动态：点赞人员记录
                Router::get('/getPraises', 'DynamicController@getPraises');
            });

            /**
             * 荟吧相关
             */
            // 荟吧列表
            Router::get('topics', 'TopicController@lists');
            Router::addGroup('topic', function () {
                // 荟吧详情
                Router::get('/detail', 'TopicController@detail');
                // 荟吧的动态列表
                Router::get('/dynamics', 'TopicController@dynamics');
                // 关注指定荟吧
                Router::post('/follow', 'TopicController@follow', ['middleware' => CheckAuth::class]);
            });
        }, ['middleware' => GetUserByToken::class]);

        // 登录会员
        Router::addGroup('', function () {
            // 文件上传
            Router::post('upload_file', 'UploadController@file');
            // 批量文件上传
            Router::post('upload_files', 'UploadController@files');

            /**
             * 好友相关
             */
            Router::addGroup('user', function () {
                // 好友列表
                Router::get('/friends', 'FriendController@friends');
                // 我关注的会员
                Router::get('/follows', 'FriendController@follows');
                // 我关注的荟吧
                Router::get('/follow_topics', 'TopicController@follows');
                // 关注指定会员
                Router::post('/follow', 'FriendController@follow');
                // 指定会员，设置是否特别关注
                Router::put('/setSpecial', 'FriendController@setSpecial');
                // 指定会员，设置是否拉黑
                Router::put('/setBlacklist', 'FriendController@setBlacklist');
                // 我的粉丝
                Router::get('/fans', 'FriendController@fans');


                // 我的收藏
                Router::get('/collections', 'DynamicController@collections');
                // 今日签到信息
                Router::get('/getSignByToday', 'SignController@getSignByToday');
                // 每日签到
                Router::post('/signIn', 'SignController@signIn');
                // 指定月份的签到状态
                Router::get('/getSignsStatusByMonth', 'SignController@getSignsStatusByMonth');
                // 我的签到记录：按月筛选
                Router::get('/getSignsByMonth', 'SignController@getSignsByMonth');
                // 编辑个人资料
                Router::match(['put', 'patch'], '/update', 'IndexController@update');
                // 编辑扩展信息
                Router::match(['put', 'patch'], '/extend', 'IndexController@extend');
                // 更换头像
                Router::match(['put', 'patch'], '/updateAvatar', 'IndexController@updateAvatar');
                // 更换背景封面图
                Router::put('/updateBackgroundCover', 'IndexController@updateBackgroundCover');
                // 更改登录密码
                Router::put('/changePassword', 'IndexController@changePassword');
                // 更改登录密码时，通过邮箱：发送验证码
                Router::get('/sendMailByChangePassword', 'IndexController@sendMailByChangePassword')->withoutMiddleware(CheckAuth::class);
                // 通过邮箱更改登录密码
                Router::put('/changePassByEmail', 'IndexController@changePassByEmail');
                // 更改邮箱，发送邮件
                Router::post('/changeEmail', 'IndexController@changeEmail');
                // 指定会员是否在黑名单：前期先测试数据返回
                Router::get('/getUserBlackExists', 'TestController@getUserBlackExists');
            });

            /**
             * 动态相关
             */
            Router::addGroup('dynamic', function () {
                // 发布 - 动态
                Router::post('/push', 'DynamicController@push');
                // 编辑动态
                Router::patch('/update/{dynamic_id}', 'DynamicController@update');
                // 点赞 - 动态
                Router::post('/praise', 'DynamicController@praise');
                // 收藏 - 动态
                Router::post('/collection', 'DynamicController@collection');
                // 评论 - 动态
                Router::post('/comment', 'DynamicController@comment');
                // 删除评论 - 动态
                Router::delete('/delete/comment', 'DynamicController@deleteComment');
            });
            Router::addGroup('comment', function () {
                // 点赞 - 评论
                Router::post('/praise', 'CommentController@praise');
            });

            /**
             * 消息相关
             */
            Router::addGroup('', function () {
                // 我的未读消息
                Router::get('/notify/unread', 'NotifyController@unread');
                // 我的所有消息通知
                Router::get('/getNotify', 'NotifyController@getNotify');
                // 我的{提醒|系统}消息通知
                Router::get('/getSystemByNotify', 'NotifyController@getSystemByNotify');
                // 我的{点赞}消息通知
                Router::get('/getPraiseByNotify', 'NotifyController@getPraiseByNotify');
                // 我的{评论}消息通知
                Router::get('/getCommentByNotify', 'NotifyController@getCommentByNotify');
            });

            // 登录日志记录
            Router::get('/user/login_logs', 'LoginLogController@getListsByMonth');
        }, ['middleware' => CheckAuth::class]);

        // 其它系统配置
        Router::addGroup('', function () {
            // 首页启动图
            Router::get('start_diagrams', [WebSitesController::class, 'getStartDiagrams']);
            // 注册协议
            Router::get('register_agreement', [WebSitesController::class, 'registerAgreement']);
            // 关于我们
            Router::get('about_us', [WebSitesController::class, 'aboutUs']);
            // Banner图
            Router::get('banners', [WebSitesController::class, 'banners']);
            // 检测APP版本是否升级
            Router::get('check_app_version', [WebSitesController::class, 'checkAppVersion']);

            // 预览markdown的语法
            Router::post('previewMarkdown', [WebSitesController::class, 'previewMarkdown']);
        });
    },
    [
        'middleware' => [
            RecordWebLog::class,
        ],
    ]
);
