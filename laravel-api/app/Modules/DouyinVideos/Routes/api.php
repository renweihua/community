<?php

use Illuminate\Http\Request;
use App\Modules\Bbs\Http\Middleware\RecordWebLog;

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

Route::prefix('douyin')->middleware([RecordWebLog::class])->group(function () {
    Route::get('/recommend', 'IndexController@recommend');
});
