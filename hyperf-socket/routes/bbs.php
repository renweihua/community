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

Router::addGroup(
    '/api/',
    function() {
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
            App\Middleware\CorsMiddleware::class,
            App\Middleware\Bbs\RecordWebLog::class,
        ],
    ]
);
