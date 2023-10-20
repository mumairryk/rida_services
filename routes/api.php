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
    Route::post('/home/{division?}', 'CMS@home')->name('home');    
    Route::post('signup', 'AuthController@signup')->name('signup');
    Route::post('resend_phone_code', 'AuthController@resend_phone_code')->name('resend_phone_code');
    Route::post('confirm_phone_code', 'AuthController@confirm_phone_code')->name('confirm_phone_code');
    Route::post('social_login', 'AuthController@social_login')->name('social_login');
    Route::post('login', 'AuthController@email_login')->name('api.login');
    Route::post('forgot_password','AuthController@forgot_password')->name('api.forgot_password');
    Route::post('reset_password','AuthController@reset_password')->name('api.reset_password');
    Route::post('resend_forgot_password_otp','AuthController@resend_forgot_password_otp')->name('api.resend_forgot_password_otp');

    Route::post('/get_page','CMS@get_page');
    Route::post('/get_faq','CMS@get_faq');
    Route::post('/get_help','CMS@gethelp');

    Route::post('/update_user_profile','UsersController@update_user_profile');
    Route::post('/change_phone_number','UsersController@change_phone_number');
    Route::post('/validate_otp_phone_email_update','UsersController@validate_otp_phone_email_update');
    Route::post('/change_email','UsersController@change_email');
    Route::post('/my_profile', 'UsersController@my_profile')->name('my_profile');
    Route::post('/change_password','UsersController@change_password');
    
    Route::post('fav_products', 'ProductController@fav_list')->name('products_fav_list');

    Route::post('/add_address', 'UsersController@add_address')->name('add_address');
    Route::post('/edit_address', 'UsersController@edit_address')->name('edit_address');
    Route::post('/delete_address', 'UsersController@delete_address')->name('delete_address');
    Route::post('/set_default_address', 'UsersController@set_default_address')->name('set_default_address');
    Route::post('/list_address', 'UsersController@list_address')->name('list_address');
    Route::post('/set_default', 'UsersController@setdefault')->name('set_default');

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


   Route::post('/my_orders','OrderController@my_orders');
   Route::post('/my_received_orders','OrderController@my_received_orders');
   Route::post('/my_order_details','OrderController@my_order_details');


   Route::post('/questionnaire','QuestionnaireController@questionnaire');
   Route::post('/enquiry','QuestionnaireController@enquiry');
   Route::post('/my_enquiries','QuestionnaireController@my_enquiries');

  Route::get('/countries', 'CMS@countrylist')->name('countries');
   Route::post('/states', 'CMS@states')->name('states');
  Route::post('/cities', 'CMS@cities')->name('cities');
  Route::post('/submit_contact_us','CMS@submit_contact_us');

  Route::post('get_mobile_otp', 'ChangeMobileController@get_mobile_otp');
  Route::post('resend_mobile_otp',  'ChangeMobileController@resend_mobile_otp');
  Route::post('change_mobile', 'ChangeMobileController@change_mobile');


    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('delete_account', 'AuthController@delete_account')->name('delete_account');

    //Master Apis
    Route::get('/project_purpose','MasterController@getProjectPurpose');
    Route::get('/room_type','MasterController@getRoomType');
    Route::get('/square_footage','MasterController@getSquareFootage');
    Route::get('/type_of_property','MasterController@getTypeOfProperty');
    Route::get('/type_of_property','MasterController@getTypeOfProperty');
    Route::get('/divisions','MasterController@getDivisions');
});
