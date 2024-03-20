<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('save-attendance','AttendanceController@store_logs');
Route::post('save-attendance-hk','AttendanceController@store_logs_hk');
Route::get('get-last-id/{company}','AttendanceController@getlastId');
Route::get('get-last-id-hk/{company}','AttendanceController@getlastIdHK');
