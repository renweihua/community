<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/es', function () {
    $dynamics = \App\Models\Dynamic\Dynamic::search('安装')
        // ->where('is_check', '1')
        // specify columns to select
        // ->select(['dynamic_id', 'dynamic_title', 'dynamic_type', 'dynamic_content', 'created_time'])
        // filter
        // ->where('color', 'red')
        // sort
        // ->orderBy('created_time', 'DESC')
        // collapse by field
        // ->collapse('brand')
        // set offset
        // ->from(0)
        // set limit
        // ->take(10)
        // get results
        // ->get()
        ->paginate()
    ;

    return response()->json($dynamics, 200, [], 256);
   return view('welcome');
});
