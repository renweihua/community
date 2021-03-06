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
use App\Http\Middleware\CheckIpBlacklist;

Route::prefix('douyinvideos')->middleware([\App\Modules\Bbs\Http\Middleware\RecordWebLog::class, CheckIpBlacklist::class])->group(function() {
    Route::get('/', 'DouyinVideosController@index');
});
