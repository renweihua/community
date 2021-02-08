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
use App\Modules\Bbs\Http\Middleware\RecordWebLog;
use App\Modules\Bbs\Http\Middleware\RestrictIPAccess;

Route::prefix('')->middleware([
    RecordWebLog::class,
    RestrictIPAccess::class,
])->group(function() {
    Route::get('/', 'BbsController@index');

    // 文章的时间轴归档
    // Route::get('/time-axis', 'BbsController@timeAxis');

    Route::get('/label/{label}', 'BbsController@label');
    // 详情
    Route::get('/detail/{article_id}', 'BbsController@detail');
    // 自定义菜单路由访问
    Route::get('/{menu_url}', 'BbsController@menuUrl');
});
