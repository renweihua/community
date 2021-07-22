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
])->group(function() {
    Route::get('/', 'BbsController@index');

    // 第三方登录
    Route::get('oauth/{oauth}', 'OauthController@redirect');
    // 第三方登录的回调
    Route::get('oauth/{oauth}/callback', 'OauthController@callback');
});
