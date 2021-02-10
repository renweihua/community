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


Route::prefix('')->middleware([])->group(function() {
    // Auth
    Route::prefix('auth')->group(function() {
        Route::any('login', 'AuthController@login');
        Route::post('me', 'AuthController@me')->middleware(CheckAuth::class);
        Route::post('logout', 'AuthController@logout');
    });
});
