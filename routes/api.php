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
    Route::get('/home', 'CMS@home')->name('home');
    
    Route::post('signup', 'AuthController@signup')->name('signup');
    Route::post('resend_phone_code', 'AuthController@resend_phone_code')->name('resend_phone_code');
    Route::post('confirm_phone_code', 'AuthController@confirm_phone_code')->name('confirm_phone_code');
    Route::post('social_login', 'AuthController@social_login')->name('social_login');
    Route::post('login', 'AuthController@email_login')->name('api.login');
    Route::post('forgot_password','AuthController@forgot_password')->name('api.forgot_password');
    Route::post('reset_password','AuthController@reset_password')->name('api.reset_password');
    Route::post('resend_forgot_password_otp','AuthController@resend_forgot_password_otp')->name('api.resend_forgot_password_otp');

    Route::post('stores', 'StoreController@list')->name('store_list');
    Route::post('product_like', 'ProductController@product_like_dislike')->name('product_like');
    Route::post('store_like', 'StoreController@like_dislike')->name('store_like');
    Route::post('store_details', 'StoreController@store_details')->name('store_details');

    Route::post('products', 'ProductController@list')->name('products_list');
    Route::post('all_products', 'ProductController@category_list')->name('category_list');
    Route::post('rate', 'RatingController@add_rating')->name('rate');


   Route::post('product_details', 'ProductController@details')->name('product_details');
   Route::post('add_to_cart', 'CartController@add_to_cart')->name('add_to_cart');
   Route::post('get_cart', 'CartController@get_cart')->name('get_cart');
   Route::post('update_cart', 'CartController@update_cart')->name('update_cart');
   Route::post('delete_cart', 'CartController@delete_cart')->name('delete_cart');
   Route::post('reduce_cart', 'CartController@reduce_cart')->name('reduce_cart');
   Route::post('clear_cart', 'CartController@clear_cart')->name('clear_cart');
   Route::post('checkout', 'CartController@checkout')->name('checkout');
   Route::post('payment_init', 'CartController@payment_init')->name('payment_init');
   Route::get('/payment_response','CartController@payment_response');
   Route::get('/payment_cancel','CartController@payment_cancel');
   Route::post('/place_order','CartController@place_order');
   Route::post('/apply_coupon','CartController@apply_coupon');


    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('delete_account', 'AuthController@delete_account')->name('delete_account');
});
