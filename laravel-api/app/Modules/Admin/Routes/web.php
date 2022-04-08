<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Http\Middleware\CheckAuth;
use App\Modules\Admin\Http\Middleware\CheckRabc;
use App\Modules\Admin\Http\Middleware\AdminLog;
use App\Http\Middleware\CheckIpBlacklist;

Route::prefix(cnpscy_config('admin_prefix'))
    ->middleware([CheckIpBlacklist::class, AdminLog::class])
    ->group(function() {
//    Route::get('/', 'AdminController@index');
    //后台管理路由
    Route::get('/', function(){
        return view('admin::admin');
    });

    // Auth
    Route::prefix('auth')->group(function() {
        Route::post('login', 'AuthController@login');
        Route::post('me', 'AuthController@me')->middleware(CheckAuth::class);
        Route::post('logout', 'AuthController@logout')->middleware(CheckAuth::class);
        Route::post('getRabcList', 'AuthController@getRabcList')->middleware(CheckAuth::class);
    });

    Route::middleware([CheckAuth::class])->group(function () {
        // 首页
        Route::get('indexs', 'IndexController@index');
        // 编辑登录管理员资料
        Route::put('updateAdmin', 'IndexController@update');
        // 按照日志类型的统计图数据
        Route::get('logsStatistics', 'IndexController@logsStatistics');
        // 版本的历史记录
        Route::get('versionLogs', 'IndexController@versionLogs');
        // 获取服务器状态
        Route::get('getServerStatus', 'IndexController@getServerStatus');
        // 月份表列表
        Route::get('get_month_lists', 'IndexController@getMonthList');
        // 文件上传
        Route::post('upload_file', 'UploadController@file');
        // 多图批量上传
        Route::post('upload_files', 'UploadController@files');
        // 获取文件列表
        Route::get('getFileList', 'FileController@index');
        // 删除指定文件
        Route::delete('files/delete', 'FileController@delete');
        // 移动文件到指定分组
        Route::put('files/removeFileGroup', 'FileController@removeFileGroup');


        // 获取文件分组列表
        Route::get('getGroupList', 'FileGroupController@index');
        Route::prefix('fileGroup')->group(function() {
            Route::post('/create', 'FileGroupController@create');
            Route::put('/update', 'FileGroupController@update');
            Route::delete('/delete', 'FileGroupController@delete');
        });

        // 权限中间件
        Route::middleware([CheckRabc::class])->group(function () {
            // 数据库管理
            Route::prefix('database')->group(function() {
                // 数据表列表
                Route::get('/tables', 'DatabaseController@index');
                // 数据库备份
                Route::post('/backupsTables', 'DatabaseController@backupsTables');
                // 备份记录
                Route::get('/backups', 'DatabaseController@backups');
                // 删除指定备份记录
                Route::delete('/deleteBackup', 'DatabaseController@deleteBackup');
            });

            // Banner
            Route::prefix('banners')->group(function() {
                Route::get('/', 'System\BannerController@index');
                Route::post('/create', 'System\BannerController@create');
                Route::put('/update', 'System\BannerController@update');
                Route::delete('/delete', 'System\BannerController@delete');
                Route::put('/changeFiledStatus', 'System\BannerController@changeFiledStatus');
            });

            // 配置管理
            Route::prefix('configs')->group(function() {
                Route::get('/', 'System\ConfigController@index');
                Route::get('/detail', 'System\ConfigController@detail');
                Route::post('/create', 'System\ConfigController@create');
                Route::put('/update', 'System\ConfigController@update');
                Route::delete('/delete', 'System\ConfigController@delete');
                Route::put('/changeFiledStatus', 'System\ConfigController@changeFiledStatus');
                Route::get('/getConfigGroupType', 'System\ConfigController@getConfigGroupType')->withoutMiddleware([CheckRabc::class]);
                Route::put('/pushRefreshConfig', 'System\ConfigController@pushRefreshConfig');
            });

            // 友情链接
            Route::prefix('friendlinks')->group(function() {
                Route::get('/', 'System\FriendlinkController@index');
                Route::post('/create', 'System\FriendlinkController@create');
                Route::put('/update', 'System\FriendlinkController@update');
                Route::delete('/delete', 'System\FriendlinkController@delete');
                Route::put('/changeFiledStatus', 'System\FriendlinkController@changeFiledStatus');
            });

            Route::prefix('protocols')->group(function() {
                Route::get('/', 'System\ProtocolController@index');
                Route::post('/create', 'System\ProtocolController@create');
                Route::put('/update', 'System\ProtocolController@update');
                Route::delete('/delete', 'System\ProtocolController@delete');
            });

            Route::prefix('versions')->group(function() {
                Route::get('/', 'System\VersionController@index');
                Route::post('/create', 'System\VersionController@create');
                Route::put('/update', 'System\VersionController@update');
                Route::delete('/delete', 'System\VersionController@delete');
                Route::put('/changeFiledStatus', 'System\VersionController@changeFiledStatus');
            });

            Route::prefix('admins')->group(function() {
                Route::get('/', 'Rabc\AdminController@index');
                Route::post('/create', 'Rabc\AdminController@create');
                Route::put('/update', 'Rabc\AdminController@update');
                Route::delete('/delete', 'Rabc\AdminController@delete');
                Route::get('/getSelectLists', 'Rabc\AdminController@getSelectLists');
                Route::put('/changeFiledStatus', 'Rabc\AdminController@changeFiledStatus');
            });

            Route::prefix('admin_roles')->group(function() {
                Route::get('/', 'Rabc\AdminRoleController@index');
                Route::post('/create', 'Rabc\AdminRoleController@create');
                Route::put('/update', 'Rabc\AdminRoleController@update');
                Route::delete('/delete', 'Rabc\AdminRoleController@delete');
                Route::get('/getSelectLists', 'Rabc\AdminRoleController@getSelectLists')->withoutMiddleware([CheckRabc::class]);
                Route::put('/changeFiledStatus', 'Rabc\AdminRoleController@changeFiledStatus');
            });

            Route::prefix('admin_menus')->group(function() {
                Route::get('/', 'Rabc\AdminMenuController@index');
                Route::post('/create', 'Rabc\AdminMenuController@create');
                Route::put('/update', 'Rabc\AdminMenuController@update');
                Route::delete('/delete', 'Rabc\AdminMenuController@delete');
                Route::get('/getSelectLists', 'Rabc\AdminMenuController@getSelectLists')->withoutMiddleware([CheckRabc::class]);
                Route::put('/changeFiledStatus', 'Rabc\AdminMenuController@changeFiledStatus');
            });

            // 管理员日志
            Route::prefix('adminlogs')->group(function() {
                Route::get('/', 'Log\AdminLogController@index');
                Route::delete('/delete', 'Log\AdminLogController@delete');
            });

            // 管理员登录日志
            Route::prefix('adminloginlogs')->group(function() {
                Route::get('/', 'Log\AdminLoginLogController@index');
                Route::delete('/delete', 'Log\AdminLoginLogController@delete');
            });

            // 文章分类
            Route::prefix('article_categories')->group(function() {
                Route::get('/', 'Article\ArticleCategoryController@index');
                Route::post('/create', 'Article\ArticleCategoryController@create');
                Route::put('/update', 'Article\ArticleCategoryController@update');
                Route::delete('/delete', 'Article\ArticleCategoryController@delete');
                Route::get('/getSelectLists', 'Article\ArticleCategoryController@getSelectLists')->withoutMiddleware([CheckRabc::class]);
                Route::put('/changeFiledStatus', 'Article\ArticleCategoryController@changeFiledStatus');
            });

            // 文章标签
            Route::prefix('article_labels')->group(function() {
                Route::get('/', 'Article\ArticleLabelController@index');
                Route::post('/create', 'Article\ArticleLabelController@create');
                Route::put('/update', 'Article\ArticleLabelController@update');
                Route::delete('/delete', 'Article\ArticleLabelController@delete');
                Route::get('/getSelectLists', 'Article\ArticleLabelController@getSelectLists')->withoutMiddleware([CheckRabc::class]);
            });

            // 文章管理
            Route::prefix('articles')->group(function() {
                Route::get('/', 'Article\ArticleController@index');
                Route::get('/detail', 'Article\ArticleController@detail');
                Route::post('/create', 'Article\ArticleController@create');
                Route::put('/update', 'Article\ArticleController@update');
                Route::delete('/delete', 'Article\ArticleController@delete');
                Route::put('/changeFiledStatus', 'Article\ArticleController@changeFiledStatus');
            });

            // Bbs菜单栏目管理
            Route::prefix('menus')->group(function() {
                Route::get('/', 'Bbs\MenuController@index');
                Route::post('/create', 'Bbs\MenuController@create');
                Route::put('/update', 'Bbs\MenuController@update');
                Route::delete('/delete', 'Bbs\MenuController@delete');
                Route::get('/getSelectLists', 'Bbs\MenuController@getSelectLists')->withoutMiddleware([CheckRabc::class]);
                Route::put('/changeFiledStatus', 'Bbs\MenuController@changeFiledStatus');
                Route::get('/getTplTypeAndViews', 'Bbs\MenuController@getTplTypeAndViews')->withoutMiddleware([CheckRabc::class]);
            });

            // 动态管理
            Route::prefix('dynamics')->group(function() {
                Route::get('/', 'Bbs\DynamicController@index');
                Route::put('/check', 'Bbs\DynamicController@check');
                Route::put('/set_top', 'Bbs\DynamicController@setTop');
                Route::put('/set_excellent', 'Bbs\DynamicController@excellent');
            });
        });
    });
});
