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
use App\Modules\Bbs\Http\Middleware\RestrictIPAccess;

Route::prefix('')->middleware([
])->group(function() {
    var_dump(get_db_prefix());
    exit;
    Route::get('/', 'BbsController@index');
});
