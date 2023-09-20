<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::namespace('App\Http\Controllers\Api\v1')->prefix("v1")->name("api.v1.")->group(function() {
    Route::post('signup', 'AuthController@signup')->name('signup');
    Route::post('resend_phone_code', 'AuthController@resend_phone_code')->name('resend_phone_code');
    Route::post('confirm_phone_code', 'AuthController@confirm_phone_code')->name('confirm_phone_code');
    Route::post('login', 'AuthController@email_login')->name('api.login');

    
    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('delete_account', 'AuthController@delete_account')->name('delete_account');
});
