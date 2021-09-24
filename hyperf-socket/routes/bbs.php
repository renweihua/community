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

use \App\Controller\Bbs\WebSitesController;
use App\Controller\Bbs\AuthController;
use App\Middleware\Bbs\RecordWebLog;

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

        // 登录会员
        Router::addGroup('', function () {

        });

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
