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


//Registration and Login
//Route::get('countries', [Api\v1\CMS::class, 'countrylist']);




Route::namespace('App\Http\Controllers\Api\v1')->prefix("v1")->name("api.v1.")->group(function() {
    Route::get('/account-types', 'ListController@getAccountTypes')->name('account-types');
    Route::get('/activity-types/{id}', 'ListController@getActivityTypes')->name('activity-types');
    Route::get('/business-types', 'ListController@getBusinessTypes')->name('business-types');
    Route::get('/vehicle-types', 'ListController@getVehicleTypes')->name('vehicle-types');

    Route::post('/create-account', 'SignupController@createAccount')->name('create-account');
    Route::post('/create-delivery-partner', 'SignupController@createDevliveryPartner')->name('create-delivery-partner');
    Route::post('/add-bank-details', 'SignupController@addBankDetails')->name('add-bank-details');
    Route::post('/add-bank-and-company', 'SignupController@addBankAndCompany')->name('add-bank-and-company');
    Route::post('/add-location', 'SignupController@addLocation')->name('add-location');
    Route::post('/add-vehicle-details', 'SignupController@addVehicleDetails')->name('add-vehicle-details');

    //Login
    Route::post('/login', 'AuthController@signIn')->name('signIn');

    Route::post('/store_details','StoreController@store_details')->name('store_details');
    Route::post('/get_deligates','StoreController@get_deligates');
    Route::post('/get_vehicle_type','StoreController@get_vehicle_type');
    Route::post('/accept_order','OrderController@accept_order');
    Route::post('/reject_order','OrderController@reject_order');
    Route::post('/init_strip_payment','OrderController@init_strip_payment');
    Route::post('/complete_payment','OrderController@complete_payment');
    Route::post('/ready_for_delivery','OrderController@ready_for_delivery');
    Route::post('/store_deliver_order','OrderController@store_deliver_order');
    Route::post('/rate_driver','OrderController@rate_driver');
    Route::post('/rate_store','OrderController@rate_store');

    Route::post('/driver_orders','OrderController@driver_orders');
    Route::post('/driver_order_details','OrderController@driver_order_details');
    Route::post('/driver_accept_order','OrderController@driver_accept_order');
    Route::post('/driver_collect_order','OrderController@driver_collect_order');
    Route::post('/driver_deliver_order','OrderController@driver_deliver_order');
    Route::post('driver_profile','DriverController@my_profile')->name('driver_profile');
    Route::post('my_reviews','DriverController@my_reviews')->name('my_reviews');
    // Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('/hide_un_hide_profile', 'UsersController@hide_un_hide_profile')->name('hide_un_hide_profile');
    Route::post('/driver_mute_all_order','OrderController@driver_mute_all_order');

    Route::post('/place_service_request','ServiceRequestController@place_service_request');
    Route::post('/my_service_requests','ServiceRequestController@my_service_requests');
    Route::post('/my_service_details','ServiceRequestController@my_service_details');
    Route::post('/provider_service_requests','ServiceRequestController@provider_service_requests');
    Route::post('/provider_service_details','ServiceRequestController@provider_service_details');
    Route::post('/accept_service_request','ServiceRequestController@accept_service_request');
    Route::post('/reject_service_request','ServiceRequestController@reject_service_request');
    Route::post('/accept_quote','ServiceRequestController@accept_quote');
    Route::post('/reject_quote','ServiceRequestController@reject_quote');
    
});




//Route::get('countries', [Api\v1\CMS::class, 'countrylist']);
Route::namespace('App\Http\Controllers\Api\v1')->prefix("v1")->name("api.v1.")->group(function(){
  Route::get('/countries', 'CMS@countrylist')->name('countries');
  Route::post('products', 'ProductController@list')->name('products_list');
  Route::post('all_products', 'ProductController@category_list')->name('category_list');
  
  Route::get('/home', 'CMS@home')->name('home');
  Route::get('/species', 'BaseController@species')->name('species');
  Route::post('/breed', 'BaseController@breed')->name('breed');
  Route::post('/food', 'BaseController@food')->name('food');
  Route::get('/cage_types', 'BaseController@cage_types')->name('cage_types');
  Route::post('/doctors', 'BaseController@doctors')->name('doctors');
  Route::post('/doctor_dates', 'BaseController@doctor_dates')->name('doctor_dates');
  Route::post('/doctor_timings', 'BaseController@doctor_timings')->name('doctor_timings');
  Route::post('/doctor_timeslots', 'BaseController@doctor_timeslots')->name('doctor_timeslots');
  Route::post('/vendor_availability', 'BaseController@vendor_availability')->name('vendor_availability');

  Route::post('/appointment_types', 'BaseController@appointment_types')->name('appointment_types');
  Route::post('/grooming_types', 'BaseController@grooming_types')->name('grooming_types');
  Route::post('/feeding_schedules', 'BaseController@feeding_schedules')->name('feeding_schedules');
  Route::post('/groomers', 'BaseController@groomers')->name('groomers');
  Route::post('/groomer_timings', 'BaseController@groomer_timings')->name('groomer_timings');
  Route::post('/groomer_timeslots', 'BaseController@groomer_timeslots')->name('groomer_timeslots');
  Route::post('/groomer_dates', 'BaseController@groomer_dates')->name('groomer_dates');
  

  // get_doggy_play_time_dates

  Route::post('/get_doggy_play_time_dates_list', 'DoggyPlayTimeDatesController@get_doggy_play_time_dates_list')->name('get_doggy_play_time_dates_list');
  Route::post('/get_doggy_play_time_date/{date}', 'DoggyPlayTimeDatesController@get_doggy_play_time_date')->name('get_doggy_play_time_date');
  Route::post('/checkout_doggy_playtime','ServiceRequestController@checkout_doggy_playtime');
  Route::post('/verify_booking_doggy_playtime','ServiceRequestController@verify_booking_doggy_playtime');


  Route::post('/my_pets', 'UsersController@my_pets')->name('my_pets');
  Route::post('/add_pet', 'UsersController@add_pet')->name('add_pet');
  Route::post('/get_pet', 'UsersController@get_pet')->name('get_pet');
  Route::post('/update_pet', 'UsersController@update_pet')->name('update_pet');
  Route::post('/delete_pet', 'UsersController@delete_pet')->name('delete_pet');

  Route::post('/book_veterinary_service','ServiceRequestController@book_veterinary_service');
  Route::post('/book_grooming_service','ServiceRequestController@book_grooming_service');
  Route::post('/book_boarding_service','ServiceRequestController@book_boarding_service');
  Route::post('/day_care_reservation','ServiceRequestController@day_care_reservation');

  Route::post('/my_bookings','ServiceRequestController@my_bookings');
  Route::post('/my_booking_details','ServiceRequestController@my_booking_details');
  Route::post('/accept_quote','ServiceRequestController@accept_quote');
  Route::post('/cancel_quote','ServiceRequestController@cancel_quote');
  
  Route::post('/contact_us','BaseController@contact_us');


  // Route::get('/categories', 'CMS@categorylist')->name('categorylist');
  Route::match(array('GET','POST'),'/categories', 'CMS@categorylist')->name('categorylist');
  Route::post('/states', 'CMS@states')->name('states');
  Route::post('/cities', 'CMS@cities')->name('cities');
  
  Route::post('/add_address', 'UsersController@add_address')->name('add_address');
  Route::post('/edit_address', 'UsersController@edit_address')->name('edit_address');
  Route::post('/delete_address', 'UsersController@delete_address')->name('delete_address');
  Route::post('/set_default_address', 'UsersController@set_default_address')->name('set_default_address');
  Route::post('/list_address', 'UsersController@list_address')->name('list_address');
  Route::post('/set_default', 'UsersController@setdefault')->name('set_default');

  Route::post('/get_loged_users', 'UsersController@get_loged_users')->name('get_loged_users');
  Route::post('/get_tag_users', 'PostController@get_tag_users')->name('get_tag_users');
  Route::post('/add_post', 'PostController@add_post')->name('add_post');
  Route::post('/like_dislike', 'PostController@like_dislike')->name('like_dislike');
  Route::post('/post_comment', 'PostController@post_comment')->name('post_comment');
  Route::post('/comment_like_dislike', 'PostController@comment_like_dislike')->name('comment_like_dislike');
  Route::post('/search_user', 'UsersController@search_user')->name('search_user');
  Route::post('/follow_unfollow_user', 'UsersController@follow_unfollow_user')->name('follow_unfollow_user');
  Route::post('/my_profile', 'UsersController@my_profile')->name('my_profile');
  Route::post('/toggle_private_profile', 'UsersController@toggle_private_profile')->name('toggle_private_profile');
  Route::post('/view_profile', 'UsersController@view_profile')->name('view_profile');
  Route::post('/view_profile_by_username', 'UsersController@view_profile_by_username')->name('view_profile_by_username');
  Route::post('/get_user_posts', 'PostController@get_user_posts')->name('get_user_posts');
  Route::post('/get_posts', 'PostController@get_posts')->name('get_posts');
  Route::post('/update_user_profile','UsersController@update_user_profile');
  Route::post('/change_phone_number','UsersController@change_phone_number');
  Route::post('/validate_otp_phone_email_update','UsersController@validate_otp_phone_email_update');
  Route::post('/change_email','UsersController@change_email');
  Route::post('/change_password','UsersController@change_password');
  Route::post('/get_page','CMS@get_page');
  Route::post('/get_faq','CMS@get_faq');
  Route::post('/get_help','CMS@gethelp');
  Route::post('/save_unsave_post', 'PostController@save_unsave_post')->name('save_unsave_post');
  Route::post('/get_saved_posts', 'PostController@get_saved_posts')->name('get_saved_posts');
  Route::post('/remove_post', 'PostController@remove_post')->name('remove_post');
  Route::post('/hide_post', 'PostController@hide_post')->name('hide_post');
  Route::post('/get_comments', 'PostController@get_comments')->name('get_comments');
  Route::post('/accept_follow_request', 'UsersController@accept_follow_request')->name('accept_follow_request');
  Route::post('/my_followers_list', 'UsersController@my_followers_list')->name('my_followers_list');
  Route::post('/my_following_list', 'UsersController@my_following_list')->name('my_following_list');
  Route::post('/remove_follower', 'UsersController@remove_follower')->name('remove_follower');
  Route::post('/get_user_posts_list', 'PostController@get_user_posts_list')->name('get_user_posts_list');
  Route::post('/profile_search', 'PostController@profile_search')->name('profile_search');
  Route::post('/get_post_details', 'PostController@get_post_details')->name('get_post_details');
  Route::post('/others_following_list', 'UsersController@others_following_list')->name('others_following_list');
  Route::post('/others_followers_list', 'UsersController@others_followers_list')->name('others_followers_list');
  Route::post('/report_user', 'UsersController@report_user')->name('report_user');
  Route::post('/get_nearest_users', 'UsersController@get_nearest_users')->name('get_nearest_users');
  Route::post('/get_cross_by_users', 'UsersController@get_cross_by_users')->name('get_cross_by_users');
  Route::post('/mark_story_viewed', 'StoryController@mark_story_viewed')->name('mark_story_viewed');
  Route::post('/get_post_likes_list', 'PostController@get_post_likes_list')->name('get_post_likes_list');
  Route::post('/add_post_test', 'PostController@add_post_test')->name('add_post_test');
  Route::post('industry_types', 'StoreController@industry_types')->name('industry_types');
  Route::post('stores', 'StoreController@list')->name('store_list');
  Route::post('product_like', 'ProductController@product_like_dislike')->name('product_like');
  Route::post('store_like', 'StoreController@like_dislike')->name('store_like');
  Route::post('store_details', 'StoreController@store_details')->name('store_details');
  
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
  Route::post('/received_order_details','OrderController@received_order_details');
  Route::post('fav_products', 'ProductController@fav_list')->name('products_fav_list');
  Route::post('fav_stores', 'StoreController@fav_list')->name('store_fav_list');
  Route::post('/cancel_order','OrderController@cancel_order');
  Route::post('/return_order_item','OrderController@return_order_item');
  Route::post('/wallet_payment_init','UsersController@wallet_payment_init');
  Route::post('/wallet_recharge','UsersController@wallet_recharge');
  Route::post('/wallet_details','UsersController@wallet_details');
  Route::get('/bio_list', 'UsersController@bio_list')->name('bio_list');
  Route::post('/my_insight', 'UsersController@my_insight')->name('my_insight');
  Route::post('list_moda_products', 'ProductController@list_moda_products')->name('list_moda_products');
  Route::post('moda_categories', 'ModaController@moda_categories');
  Route::get('hair_colors', 'ModaController@hair_colors');
  Route::get('skin_colors', 'ModaController@skin_colors');
  Route::get('colors', 'ModaController@colors');
  Route::post('/comment_delete', 'PostController@comment_delete')->name('comment_delete');
  Route::post('add_product_to_moda', 'ModaController@add_product_to_moda');
  Route::post('my_moda', 'ModaController@my_moda');
  Route::post('previous_moda_list', 'ModaController@previous_moda_list');
  Route::post('/get_user_connection_details', 'UsersController@get_user_connection_details')->name('get_user_connection_details');
  Route::post('moda_fav', 'ModaController@moda_fav');
  Route::post('favourite_moda', 'ModaController@favourite_moda');
  Route::post('favourite_moda_products', 'ModaController@favourite_moda_products');
  Route::post('remove_fav_moda', 'ModaController@remove_fav_moda');
  Route::post('view_moda_products', 'ModaController@view_moda_products');
  Route::post('moda_product_fav', 'ModaController@moda_product_fav');
  Route::post('save_moda_color', 'ModaController@save_moda_color');
  Route::post('get_store_by_category', 'ModaController@get_store_by_category');
  Route::post('get_moda_store_categories', 'ModaController@get_moda_store_categories');

  Route::post('/get_user_posts_new', 'PostController@get_user_posts_list')->name('get_user_posts_new');
  Route::post('/get_saved_posts_new', 'PostController@get_saved_posts_new')->name('get_saved_posts_new');
  Route::post('/tage_post_search', 'PostController@tage_post_search')->name('tage_post_search');
  //Stories
  Route::post('add_story','StoryController@add_story');
  Route::post('get_my_own_stories','StoryController@get_my_own_stories');
  Route::post('remove_story','StoryController@remove_story');
  Route::post('get_stories','StoryController@get_stories');
  Route::post('get_user_stories','StoryController@get_user_stories');
  Route::post('get_story_details','StoryController@get_story_details');
  Route::post('get_story_another_user','StoryController@get_story_another_user');
  Route::post('/hide_unhide_story', 'StoryController@hide_unhide_story')->name('hide_unhide_story');
  Route::post('/block_unblock_user', 'StoryController@block_unblock_user')->name('block_unblock_user');
  Route::post('/get_message_privacy', 'UsersController@get_message_privacy')->name('get_message_privacy');
  Route::post('/update_message_settings', 'UsersController@update_message_settings')->name('update_message_settings');
  Route::post('/story_like_dislike', 'StoryController@like_dislike')->name('story_like_dislike');
  Route::post('/get_report_user_problems', 'UsersController@get_report_user_problems')->name('get_report_user_problems');
  Route::get('/get_public_business_infos', 'UsersController@get_public_business_infos')->name('get_public_business_infos');
  Route::post('/get_hash_tags','PostController@get_hash_tags');
  Route::post('/start_live', 'Wowza_Controller@start_live')->name('start_live');
  Route::post('/stop_recording', 'Wowza_Controller@stop_recording')->name('stop_recording');
  Route::post('/report_post', 'PostController@report_post')->name('report_post');
  Route::post('/fav_unfav_user', 'UsersController@fav_unfav_user')->name('fav_unfav_user');
  Route::post('/get_fav_users', 'UsersController@get_fav_users')->name('get_fav_users');
  Route::post('/report_story', 'StoryController@report_story')->name('report_story');
  Route::post('/mute_unmute_story', 'StoryController@mute_unmute_story')->name('mute_unmute_story');

  // food apis
  // get stores
  Route::get('/get_stores', 'FoodController@get_stores')->name('get_stores');
  Route::get('/get_store_details', 'FoodController@get_store_details')->name('get_store_details');
  Route::get('/get_food_categories', 'FoodController@get_food_categories')->name('get_food_categories');
  Route::get('/get_menus_by_category', 'FoodController@get_menus_by_category')->name('get_menus_by_category');
  Route::post('/add_food_to_cart', 'FoodCartController@add_food_to_cart')->name('add_food_to_cart');
  Route::get('/list_food_cart', 'FoodCartController@list_food_cart')->name('list_food_cart');
  Route::post('apply_food_coupon', 'FoodCartController@apply_food_coupon')->name('apply_food_coupon');
});

Route::namespace('App\Http\Controllers\Api\v1')->prefix("v1/auth")
// ->name("api.v1.auth")
->group(function(){

  Route::post('login', 'AuthController@email_login')->name('api.login');
  Route::post('logout', 'AuthController@logout')->name('logout');
  Route::post('forgot_password','AuthController@forgot_password')->name('api.forgot_password');
  Route::post('reset_password','AuthController@reset_password')->name('api.reset_password');
  Route::post('resend_forgot_password_otp','AuthController@resend_forgot_password_otp')->name('api.resend_forgot_password_otp');


  Route::post('signup', 'AuthController@signup')->name('signup');
  Route::post('resend_code', 'AuthController@resend_code')->name('resend_code');
  Route::post('confirm_code', 'AuthController@confirm_code')->name('confirm_code');
  Route::post('email_login', 'AuthController@email_login')->name('email_login');
  Route::post('mobile_login', 'AuthController@mobile_login')->name('mobile_login');
  Route::post('social_login', 'AuthController@social_login')->name('social_login');
  Route::post('common_login', 'AuthController@common_login')->name('common_login');

  Route::post('resend_phone_code', 'AuthController@resend_phone_code')->name('resend_phone_code');
  Route::post('confirm_phone_code', 'AuthController@confirm_phone_code')->name('confirm_phone_code');
  Route::post('get_user_by_token', 'AuthController@get_user_by_token')->name('get_user_by_token');


  Route::post('get_mobile_otp', 'ChangeMobileController@get_mobile_otp');
  Route::post('resend_mobile_otp',  'ChangeMobileController@resend_mobile_otp');
  Route::post('change_mobile', 'ChangeMobileController@change_mobile');

  Route::post('upload_data', 'ChangeMobileController@upload_data');


  
  Route::post('switch_account', 'AuthController@switch_account')->name('switch_account');
  Route::post('delete_account', 'AuthController@delete_account')->name('delete_account');
});


// Route::namespace('App\Http\Controllers\Api\v1')->prefix("v1/auth")->name("api.v1.auth")->group(function(){
  
// });

