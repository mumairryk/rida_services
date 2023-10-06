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

// Route::get('/', function () {
//     //broadcast(new DemoEvent('karthik'));
//     return view('welcome');
// });

Route::get('/clear', 'App\Http\Controllers\admin\LoginController@clear')->name('clear');

Route::get('/create-mc-client', 'App\Http\Controllers\MediaConverterController@createClient');
Route::get('/reset_password_auth', 'App\Http\Controllers\AjaxContoller@reset_password');
Route::get('/reset_password_auth/{id}', 'App\Http\Controllers\AjaxContoller@reset_password');
Route::post('/submit_reset_request', 'App\Http\Controllers\AjaxContoller@submit_reset_request')->name('submit_reset_request');
// Route::get('/', 'App\Http\Controllers\front\HomeController@index');
Route::get('/register', 'App\Http\Controllers\front\HomeController@register');
Route::post('/save_vendor', 'App\Http\Controllers\front\HomeController@save_vendor');
Route::post('/checkAvailability', 'App\Http\Controllers\front\HomeController@checkAvailability');
Route::post("common/states/get_by_country", "App\Http\Controllers\admin\StatesController@get_by_country");
Route::post("common/cities/get_by_state", "App\Http\Controllers\admin\CitiesController@get_by_state");
Route::get('/reset_password', 'App\Http\Controllers\front\HomeController@reset_password');
Route::get('/reset_password/{id}', 'App\Http\Controllers\front\HomeController@reset_password');
Route::post('/new_password', 'App\Http\Controllers\front\HomeController@new_password')->name('vendor.new_password');
Route::any('/update_location', 'App\Http\Controllers\front\HomeController@update_location')->name('update_location');
//Auth::routes();
Route::post("get_moda_sub_category_by_category", "App\Http\Controllers\admin\ModaCategories@moda_sub_category_by_category");

Route::get('/admin', 'App\Http\Controllers\admin\LoginController@login')->name('admin.login');
Route::get('/admin/login', 'App\Http\Controllers\admin\LoginController@login')->name('admin.alogin');
Route::post('admin/check_login', 'App\Http\Controllers\admin\LoginController@check_login')->name('admin.check_login');
Route::namespace('App\Http\Controllers\admin')->prefix('admin')->middleware('admin')->name('admin.')->group(function () {


    Route::post('check_exciting_event', 'DoggyPlayTimeDatesController@check_exciting_event')->name('check_exciting_event');
    Route::get('vendor/dates/{vendor_id}', 'DoggyPlayTimeDatesController@getDiningDates')->name('vendor.doggy.dates');
    Route::post('vendor/add-dates', 'DoggyPlayTimeDatesController@addDateToService')->name('vendor.add-dates');
    Route::get('vendor_update_dates_data', 'DoggyPlayTimeDatesController@dining_update_dates_data')->name('dining_update_dates_data');
    Route::post('vendor/delete-date', 'DoggyPlayTimeDatesController@deleteDates')->name('vendor.delete-date');
    Route::post('vendor/delete-date-request', 'DoggyPlayTimeDatesController@deleteDateRequest')->name('vendor.delete-date-request');


    Route::get('change-password', 'AdminController@changePassword')->name('change.password');
    Route::post('change-password', 'AdminController@changePasswordSave')->name('change.password.save');
    Route::get('logout', 'LoginController@logout')->name('logout');
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::get('import_export', 'ProductImportExport@import_export')->name('import_export');
    Route::post('Excel/upload_file', 'ProductImportExport@upload_file')->name('upload_file');
    Route::get('start_import', 'ProductImportExport@start_import')->name('start_import');
    Route::post('Excel/upload_zip_file', 'ProductImportExport@upload_zip_file')->name('upload_zip_file');
    Route::post('Excel/startUnzipImage', 'ProductImportExport@startUnzipImage')->name('startUnzipImage');
    Route::post('Excel/export', 'ProductImportExport@export_product')->name('export_product_post');
    Route::get('Excel/export', 'ProductImportExport@export_product')->name('export_product');

    Route::get('report/customers', 'ReportController@users')->name('customer_report');
    Route::get('report/vendors', 'ReportController@vendors')->name('vendor_report');
    Route::get('report/stores', 'ReportController@stores')->name('store_report');
    Route::get('report/orders', 'ReportController@orders')->name('order_report');
    Route::get('report/commission', 'ReportController@commission')->name('commission_report');
    Route::get('report/out_of_stock', 'ReportController@outofstock')->name('out_of_stock');


    Route::get("category", "Category@index");
    Route::get("category/create", "Category@create");
    Route::post("category/change_status", "Category@change_status");
    Route::get("category/edit/{id}", "Category@edit");
    Route::delete("category/delete/{id}", "Category@destroy");
    Route::post("save_category", "Category@store");
    Route::match(array('GET', 'POST'), 'category/sort', 'Category@sort');
    //Division Routes
    Route::get("division", "Division@index");
    Route::get("division/create", "Division@create");
    Route::post("save_division", "Division@store");
    Route::post("division/change_status", "Category@change_status");
    Route::get("division/edit/{id}", "Division@edit");
    Route::delete("division/delete/{id}", "Division@destroy");
    // food category routes
    Route::get("food_category", "FoodCategoryController@index");
    Route::get("food_category/create", "FoodCategoryController@create");
    Route::post("food_category/change_status", "FoodCategoryController@change_status");
    Route::get("food_category/edit/{id}", "FoodCategoryController@edit");
    Route::delete("food_category/delete/{id}", "FoodCategoryController@destroy");
    Route::post("save_food_category", "FoodCategoryController@store");
    Route::match(array('GET', 'POST'), 'food_category/sort', 'FoodCategoryController@sort');

    Route::get("brand", "Brand@index");
    Route::get("brand/create", "Brand@create");
    Route::post("brand/change_status", "Brand@change_status");
    Route::get("brand/edit/{id}", "Brand@edit");
    Route::delete("brand/delete/{id}", "Brand@destroy");
    Route::post("save_brand", "Brand@store");
    Route::match(array('GET', 'POST'), 'brand/sort', 'Brand@sort');

    Route::get("deligates", "DeligateController@index");
    Route::get("deligate/create", "DeligateController@create");
    Route::post("deligates/change_status", "DeligateController@change_status");
    Route::get("deligates/edit/{id}", "DeligateController@edit");
    Route::delete("deligates/delete/{id}", "DeligateController@destroy");
    Route::post("save_deligate", "DeligateController@store");
    Route::match(array('GET', 'POST'), 'brand/sort', 'Brand@sort');



    Route::get("account_type", "AccountTypeController@index");
    Route::get("account_type/create", "AccountTypeController@create");
    Route::get("account_type/edit/{id}", "AccountTypeController@edit");
    Route::delete("account_type/delete/{id}", "AccountTypeController@destroy");
    Route::post("save_account_type", "AccountTypeController@store");

    Route::get("activity_type", "ActivityTypeController@index");
    Route::get("activity_type/create", "ActivityTypeController@create");
    Route::get("activity_type/edit/{id}", "ActivityTypeController@edit");
    Route::delete("activity_type/delete/{id}", "ActivityTypeController@destroy");
    Route::post("save_activity_type", "ActivityTypeController@store");
    Route::post("get_activities", "ActivityTypeController@get_activities")->name('get_activities');


    Route::get("industry_type", "IndustryTypesController@index");
    Route::get("industry_type/create", "IndustryTypesController@create");
    Route::post("industry_type/change_status", "IndustryTypesController@change_status");
    Route::get("industry_type/edit/{id}", "IndustryTypesController@edit");
    Route::delete("industry_type/delete/{id}", "IndustryTypesController@destroy");
    Route::post("save_industry_type", "IndustryTypesController@store");
    Route::match(array('GET', 'POST'), 'industry_type/sort', 'IndustryTypesController@sort');



    Route::resource("store_type", "StoreTypeController");
    Route::post("store_type/change_status", "StoreTypeController@change_status");
    Route::get("store_type/edit/{id}", "StoreTypeController@edit");
    Route::delete("store_type/delete/{id}", "StoreTypeController@destroy");


    Route::post("products/loadProductAttribute", "ProductController@loadProductAttribute");
    Route::post("products/loadProductVariations", "ProductController@loadProductVariations");
    Route::post("products/linkNewAttrForProduct", "ProductController@linkNewAttrForProduct");

    Route::get("products", "ProductController@index");
    Route::get("product/create/{store_id?}", "ProductController@create");
    Route::post("product/add_product", "ProductController@add_product");
    Route::get("products/edit/{id}", "ProductController@create");
    Route::delete("products/delete/{id}", "ProductController@delete_product");
    Route::delete("products/delete_doc/{id}", "ProductController@delete_document");
    Route::post("products/change_status", "ProductController@change_status");

    Route::post("products/get_by_category", "ProductController@get_by_category");

    Route::get("products_requests", "ProductController@products_requests");
    Route::get("products/add_to_product/{id}", "ProductController@add_to_product");
    Route::delete("products/delete_prd_req_doc/{id}", "ProductController@delete_prd_req_doc");
    Route::post("product/req_to_prd", "ProductController@req_to_prd");

    Route::post("products/unlinkAttrFromProduct", "ProductController@unlinkAttrFromProduct");
    Route::post("products/removeProductImage", "ProductController@removeProductImage");

    Route::get('product/export', 'ProductController@export')->name('product.export');
    Route::post('product/import', 'ProductController@import')->name('product.import');
    Route::post('product/image_upload', 'ProductController@unzip_image')->name('product.image_upload');
    Route::get('product/download_format', 'ProductController@download_format')->name('product.download_format');

    Route::get("products/store/{store_id}", "ProductController@index")->name('store.products');

    // food products 
    Route::get("food_products", "FoodProductController@index")->name('food_products');
    Route::get("food_product/create/{store_id?}", "FoodProductController@create");
    Route::post("food_product/add_product", "FoodProductController@add_product")->name('food_product.add_product');
    Route::get("food_products/edit/{id}", "FoodProductController@create");
    Route::delete("food_products/delete/{id}", "FoodProductController@delete_product");
    Route::delete("food_products/delete_doc/{id}", "FoodProductController@delete_document");
    Route::post("food_products/change_status", "FoodProductController@change_status");
    Route::post("food_products/removeProductImage", "FoodProductController@removeProductImage");

    //food heading
    Route::get('food_product/comboRow', 'FoodProductController@comboRow')->name('food_product.combo.row');
    Route::get('food_product/itemRow', 'FoodProductController@itemRow')->name('food_product.item.row');
    Route::post('food_product/heading', 'FoodItemsController@storeHeading')->name('food_product.heading.store');
    Route::post('food_product/items', 'FoodItemsController@storeItems')->name('food_product.items.store');


    Route::resource("coupons", "CouponsController");

    Route::resource("country", "CountryController");

    Route::resource("project_purpose", "ProjectPurposeController");
    Route::resource("room", "RoomController");
    Route::resource("square_footage", "SquareFootageController");
    Route::resource("aspect_of_room", "AspectofRoomController");
    Route::resource("type_of_property", "TypeofPropertyController");
    Route::resource("current_project_status", "CurrentProjectStatusController");

    Route::resource("bank", "BankController");

    Route::resource("admin_user_designation", "AdminUserDesignation");

    Route::resource("admin_users", "AdminUserController");
    Route::post("admin_users/change_status", "AdminUserController@change_status");
    Route::post("admin_users/verify", "AdminUserController@verify");
    Route::get("admin_users/update_permission/{id}", "AdminUserController@update_permission");
    Route::post("save_privilege", "AdminUserController@save_privilege");


    Route::resource("vendors", "VendorsController");
    Route::post("vendors/change_status", "VendorsController@change_status");
    Route::post("vendors/verify", "VendorsController@verify");


    Route::get("customers/blocked_users", "CustomersController@blocked_users");
    Route::get("customers/reported_users", "CustomersController@reported_users");
    Route::resource("customers", "CustomersController");
    Route::post("customers/change_status", "CustomersController@change_status");
    Route::post("customers/verify", "CustomersController@verify");

    Route::resource("wholeSellers", "WholeSellersController");
    Route::get("all", "WholeSellersController@all");
    Route::post("wholeSellers/change_status", "WholeSellersController@change_status");
    Route::post("wholeSellers/verify", "WholeSellersController@verify");
    Route::get("menu", "MenuController@menu");
    Route::post("menu/update", "MenuController@updateMenu");
    Route::get("menu/{id}/add-item", "MenuController@addItem")->name('menu.add-item');
    Route::get("menu/edit-item/{id}", "MenuController@editItem")->name('menu.edit-item');
    Route::post("menu/save-item", "MenuController@saveItem")->name('menu.save-item');

    Route::resource("delivery_representative", "DeliveryRepresentativeController");
    Route::post("delivery_representative/change_status", "DeliveryRepresentativeController@change_status");
    Route::post("delivery_representative/verify", "DeliveryRepresentativeController@verify");

    Route::resource("services_providers", "ServicesProvidersController");
    Route::post("services_providers/change_status", "ServicesProvidersController@change_status");
    Route::post("services_providers/verify", "ServicesProvidersController@verify");
    Route::get('service-requests', 'ServicesProvidersController@serviceRequests')->name('service-requests');

    Route::resource("individuals", "IndividualsController");
    Route::post("individuals/change_status", "IndividualsController@change_status");
    Route::post("individuals/verify", "IndividualsController@verify");

    Route::resource("reservations", "ReservationsController");
    Route::post("reservations/change_status", "ReservationsController@change_status");
    Route::post("reservations/verify", "ReservationsController@verify");

    Route::resource("commercial_centers", "CommercialCentersController");
    Route::post("commercial_centers/change_status", "CommercialCentersController@change_status");
    Route::post("commercial_centers/verify", "CommercialCentersController@verify");
    Route::post("commercial_centers/verify", "CommercialCentersController@verify");


    Route::resource("store_managers_type", "StoremanagersTypeController");

    Route::resource("store_managers", "StoremanagersController");
    Route::post("store_managers/change_status", "StoremanagersController@change_status");


    Route::get('product_attribute', 'AttributeController@index');
    Route::post('attribute_save', 'AttributeController@save');
    Route::post('attribute_value_save', 'AttributeController@save_atr_values');
    Route::get('attribute_edit/{id}', 'AttributeController@edit');
    Route::post("attribute/change_status", "AttributeController@change_status");

    Route::delete("attribute/delete/{id}", "AttributeController@delete_attribute");
    Route::get("attribute_values/{id}", "AttributeController@attribute_values");
    Route::delete("attribute_values/delete/{id}", "AttributeController@delete_attribute_value");
    Route::get('attribute_values_edit/{id}', 'AttributeController@edit_attribute_value');


    // Route::get("attributes","AttributeController@index");
    // Route::get("attributes/create","AttributeController@create");
    // Route::post("attributes/change_status","AttributeController@change_status");
    // Route::get("attributes/edit/{id}","AttributeController@edit");
    // Route::delete("attributes/delete/{id}","AttributeController@destroy");
    // Route::post("save_attributes","AttributeController@store");

    Route::get("attribute_values/{id}", "AttributeController@attribute_values");
    Route::post('attribute_value_save', 'AttributeController@save_atr_values');
    Route::delete("attribute_values/delete/{id}", "AttributeController@delete_attribute_value");
    Route::get('attribute_values_edit/{id}', 'AttributeController@edit_attribute_value');

    Route::get("states", "StatesController@index");
    Route::get("states/create", "StatesController@create");
    Route::post("states/change_status", "StatesController@change_status");
    Route::get("states/edit/{id}", "StatesController@edit");
    Route::delete("states/delete/{id}", "StatesController@destroy");
    Route::post("save_states", "StatesController@store");
    Route::post("states/get_by_country", "StatesController@get_by_country");

    Route::get("cities", "CitiesController@index");
    Route::get("cities/create", "CitiesController@create");
    Route::post("cities/change_status", "CitiesController@change_status");
    Route::get("cities/edit/{id}", "CitiesController@edit");
    Route::delete("cities/delete/{id}", "CitiesController@destroy");
    Route::post("save_cities", "CitiesController@store");
    Route::post("cities/get_by_state", "CitiesController@get_by_state");


    Route::get("pictures", "PicturesController@index");
    Route::post("pictures/change_status", "PicturesController@change_status");
    Route::delete("pictures/delete/{id}", "PicturesController@destroy");

    Route::get("videos", "VideosController@index");
    Route::post("videos/change_status", "VideosController@change_status");
    Route::delete("videos/delete/{id}", "VideosController@destroy");

    Route::get("store", "StoreController@index");
    Route::get("store/create", "StoreController@create");
    Route::post("store/change_status", "StoreController@change_status");
    Route::post("store/verify", "StoreController@verify");
    Route::get("store/edit/{id}", "StoreController@edit");
    Route::delete("store/delete/{id}", "StoreController@destroy");
    Route::delete("store/delete_image/{id}", "StoreController@delete_image");
    Route::post("save_store", "StoreController@store");
    Route::post("store/get_by_vendor", "StoreController@get_by_vendor");
    Route::get("store/get_all_store_list", "StoreController@get_all_store_list");
    Route::match(array('GET', 'POST'), 'store/sort', 'StoreController@sort');

    Route::get("orders", "OrderController@index");
    Route::get("order_details/{id}", "OrderController@details");
    Route::post("order/change_status", "OrderController@change_status");
    Route::post("order/cancel_order", "OrderController@cancel_order");
    Route::get("order_edit/{id}", "OrderController@edit_order");

    Route::get("wholesaler/orders", "WholesaleOrderController@index");
    Route::get("wholesaler/order_details/{id}", "WholesaleOrderController@details");
    Route::post("wholesaler/order_change_status", "WholesaleOrderController@order_change_status");
    //Banner
    Route::get("banners", "BannerController@index");
    Route::match(array('GET', 'POST'), 'banner/create', 'BannerController@create');
    Route::get("banner/edit/{id}", "BannerController@edit");
    Route::post("banner/update", "BannerController@update");
    Route::delete("banner/delete/{id}", "BannerController@delete");


    Route::get('cms_pages', 'PagesController@index')->name('cms_pages');
    Route::get('page/create', 'PagesController@create')->name('cms_pages.add');
    Route::get('page/edit/{id}', 'PagesController@edit')->name('cms_pages.edit');
    Route::post('page/save', 'PagesController@save')->name('cms_pages.save');
    Route::delete('page/delete/{id}', 'PagesController@delete')->name('cms_pages.delete');
    Route::get('contact_details', 'PagesController@contact_details')->name('contact_details');
    Route::post("contact_us_setting_store", "PagesController@contact_us_setting_store")->name('contact_us_setting_store');
    Route::get('settings', 'PagesController@settings');
    Route::post("setting_store", "PagesController@setting_store")->name('setting_store');
    
    Route::match(array('GET', 'POST'), 'questions/sort', 'QuestionsController@sort');
    Route::resource("questions", "QuestionsController");
    

    //FAQ
    Route::get("faq", "FaqController@index");
    Route::match(array('GET', 'POST'), 'faq/create', 'FaqController@create');
    Route::get("faq/edit/{id}", "FaqController@edit");
    Route::post("faq/update", "FaqController@update");
    Route::delete("faq/delete/{id}", "FaqController@delete");

    Route::get("help", "HelpController@index");
    Route::match(array('GET', 'POST'), 'help/create', 'HelpController@create');
    Route::get("help/edit/{id}", "HelpController@edit");
    Route::post("help/update", "HelpController@update");
    Route::delete("help/delete/{id}", "HelpController@delete");

    Route::post('load_vendor', 'StoreController@load_vendor');

    Route::get('notifications', 'NotificationController@notifications')->name('notifications');
    Route::get('notifications/create', 'NotificationController@create')->name('notifications.add');
    Route::post('notifications/save', 'NotificationController@save')->name('notifications.save');
    Route::delete('notifications/delete/{id}', 'NotificationController@delete')->name('notifications.delete');


    Route::match(array('GET', 'POST'), 'change_password', 'UsersController@change_password');
    Route::match(array('GET', 'POST'), 'change_user_password', 'UsersController@change_user_password');

    Route::get("moda_category", "ModaCategories@index");
    Route::get("moda_category/create", "ModaCategories@create");
    Route::post("moda_category/change_status", "ModaCategories@change_status");
    Route::get("moda_category/edit/{id}", "ModaCategories@edit");
    Route::delete("moda_category/delete/{id}", "ModaCategories@destroy");
    Route::post("save_moda_category", "ModaCategories@store");
    Route::match(array('GET', 'POST'), 'moda_category/sort', 'ModaCategories@sort');

    Route::post("moda_sub_category_by_category", "ModaCategories@moda_sub_category_by_category");
    Route::resource("skin_color", "SkinColor");
    Route::post("skin_color/change_status", "SkinColor@change_status");

    Route::resource("hair_color", "HairColor");
    Route::post("hair_color/change_status", "HairColor@change_status");

    Route::resource("public_business_infos", "PublicBusinessInfo");
    Route::post("public_business_infos/change_status", "PublicBusinessInfo@change_status");
    Route::resource("hash_tags", "HashTag");





    //irfan
    Route::get("breed", "Breed@index");
    Route::get("breed/create", "Breed@create");
    Route::post("breed/change_status", "Breed@change_status");
    Route::get("breed/edit/{id}", "Breed@edit");
    Route::delete("breed/delete/{id}", "Breed@destroy");
    Route::post("save_breed", "Breed@store");
    Route::post("breed/get_by_species", "Breed@get_by_species");

    Route::get("insurance_provider", "InsuranceProvider@index");
    Route::get("insurance_provider/create", "InsuranceProvider@create");
    Route::post("insurance_provider/change_status", "InsuranceProvider@change_status");
    Route::get("insurance_provider/edit/{id}", "InsuranceProvider@edit");
    Route::delete("insurance_provider/delete/{id}", "InsuranceProvider@destroy");
    Route::post("save_insurance_provider", "InsuranceProvider@store");


    Route::resource("pets", "Pets");
    Route::post("pets/change_status", "Pets@change_status");

    Route::get("species", "PetSpecies@index");
    Route::get("species/create", "PetSpecies@create");
    Route::post("species/change_status", "PetSpecies@change_status");
    Route::get("species/edit/{id}", "PetSpecies@edit");
    Route::delete("species/delete/{id}", "PetSpecies@destroy");
    Route::post("save_species", "PetSpecies@store");
    

    Route::resource("doctors", "Doctor");
    Route::post("doctors/change_status", "Doctor@change_status");
    Route::post("doctors/get_events", "Doctor@get_events")->name('get_events');
    Route::post("doctors/add_event", "Doctor@add_event")->name('add_event');
    Route::post("doctors/remove_event", "Doctor@remove_event")->name('remove_event');

    Route::resource("groomers", "Groomer");
    Route::post("groomers/change_status", "Groomer@change_status");

    Route::post("groomers/get_events", "Groomer@get_events")->name('groomer_get_events');
    Route::post("groomers/add_event", "Groomer@add_event")->name('groomer_add_event');
    Route::post("groomers/remove_event", "Groomer@remove_event")->name('groomer_remove_event');
    
    Route::resource("appointment_types", "AppointmentType");
    Route::post("appointment_types/change_status", "AppointmentType@change_status");

    Route::resource("appointment_times", "AppointmentTime");
    Route::post("appointment_times/change_status", "AppointmentTime@change_status");

    Route::resource("room_types", "RoomType");
    Route::post("room_types/change_status", "RoomType@change_status");

    Route::resource("feeding_schedules", "FeedingSchedule");
    Route::post("feeding_schedules/change_status", "FeedingSchedule@change_status");

    Route::resource("services", "Service");
    Route::post("services/change_status", "Service@change_status");

    Route::resource("service_quotes", "ServiceQuote");
    Route::post("service_quotes/change_status", "ServiceQuote@change_status");
    Route::get("service_quotes/view/{id}", "ServiceQuote@view");
    Route::post("service_quotes/change_quote_status", "ServiceQuote@change_quote_status");

    Route::resource("grooming_types", "GroomingType");
    Route::post("grooming_types/change_status", "GroomingType@change_status");

    Route::resource("playtime_staffs", "PlaytimeStaff");
    Route::post("playtime_staffs/change_status", "PlaytimeStaff@change_status");

    Route::resource("foods", "Food");
    Route::post("foods/change_status", "Food@change_status");
});

/***Vendors***/

Route::get('/vendor', 'App\Http\Controllers\vendor\LoginController@login')->name('vendor.login');
Route::post('vendor/check_login', 'App\Http\Controllers\vendor\LoginController@check_login')->name('vendor.check_login');
Route::get('/forgot-password', 'App\Http\Controllers\vendor\LoginController@forgotpassword')->name('vendor.forgot');
Route::post('vendor/check_user', 'App\Http\Controllers\vendor\LoginController@check_user')->name('vendor.check_user');

Route::namespace('App\Http\Controllers\vendor')->prefix('vendor_sign_up')->name('vendor_sign_up.')->group(function () {
    Route::resource("vendors", "VendorsController");
});

Route::namespace('App\Http\Controllers\vendor')->prefix('vendor')->middleware('vendor')->name('vendor.')->group(function () {

    Route::get('change-password', 'AdminController@changePassword')->name('change.password');
    Route::post('change-password', 'AdminController@changePasswordSave')->name('change.password.save');
    Route::get('logout', 'LoginController@logout')->name('logout');
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::resource("vendors", "VendorsController");

    Route::post("states/get_by_country", "StatesController@get_by_country");
    Route::post("cities/get_by_state", "CitiesController@get_by_state");

    /***Stores***/
    Route::get("store", "StoreController@index");
    Route::get("store/create", "StoreController@create");
    Route::post("store/change_status", "StoreController@change_status");
    Route::post("store/verify", "StoreController@verify");
    Route::get("store/edit/{id}", "StoreController@edit");
    Route::delete("store/delete/{id}", "StoreController@destroy");
    Route::delete("store/delete_image/{id}", "StoreController@delete_image");
    Route::post("save_store", "StoreController@store");
    Route::post("store/get_by_vendor", "StoreController@get_by_vendor");

    /***storeManager***/
    Route::resource("store_managers", "StoremanagersController");
    Route::post("store_managers/change_status", "StoremanagersController@change_status");

    Route::get("privilege", "PrivilegeController@privilege");
    Route::post("save_privilege", "PrivilegeController@save_privilege");

    Route::resource("store_managers_type", "StoremanagersTypeController");

    //designation
    Route::resource("designation", "DesignationController");

    /***Products***/
    Route::post("products/loadProductAttribute", "ProductController@loadProductAttribute");
    Route::post("products/loadProductVariations", "ProductController@loadProductVariations");
    Route::post("products/linkNewAttrForProduct", "ProductController@linkNewAttrForProduct");

    Route::get("products", "ProductController@index");

    Route::get("product/create", "ProductController@create");
    Route::post("product/add_product", "ProductController@add_product");
    Route::get("products/edit/{id}", "ProductController@create");
    Route::delete("products/delete/{id}", "ProductController@delete_product");
    Route::delete("products/delete_doc/{id}", "ProductController@delete_document");
    Route::post("products/change_status", "ProductController@change_status");
    Route::get("products_requests", "ProductController@products_requests");
    Route::get("products/add_to_product/{id}", "ProductController@add_to_product");
    Route::delete("products/delete_prd_req_doc/{id}", "ProductController@delete_prd_req_doc");
    Route::post("product/req_to_prd", "ProductController@req_to_prd");

    Route::post("products/unlinkAttrFromProduct", "ProductController@unlinkAttrFromProduct");
    Route::post("products/removeProductImage", "ProductController@removeProductImage");

    Route::get('product/export', 'ProductController@export')->name('product.export');
    Route::post('product/import', 'ProductController@import')->name('product.import');
    Route::post('product/image_upload', 'ProductController@unzip_image')->name('product.image_upload');
    Route::get('product/download_format', 'ProductController@download_format')->name('product.download_format');


    Route::get("orders", "OrderController@index");
    Route::get("order_details/{id}", "OrderController@details");
    Route::post("order/change_status", "OrderController@change_status");
    Route::post("order/change_return_status", "OrderController@change_return_status");
    Route::post("order/cancel_order", "OrderController@cancel_order");
    Route::get("order_edit/{id}", "OrderController@edit_order");

    /***VendorProfile***/
    Route::get('my_profile', 'VendorsController@MyProfile');

    Route::match(array('GET', 'POST'), 'change_password', 'UsersController@change_password');

    Route::get('import_export', 'ProductImportExport@import_export')->name('import_export');
    Route::post('Excel/export', 'ProductImportExport@export_product')->name('export_product_post');
    Route::get('Excel/export', 'ProductImportExport@export_product')->name('export_product');
    Route::post('Excel/upload_file', 'ProductImportExport@upload_file')->name('upload_file');
    Route::get('start_import', 'ProductImportExport@start_import')->name('start_import');
    Route::post('Excel/upload_zip_file', 'ProductImportExport@upload_zip_file')->name('upload_zip_file');
    Route::post('Excel/startUnzipImage', 'ProductImportExport@startUnzipImage')->name('startUnzipImage');



    Route::get("pictures", "PicturesController@index");
    Route::post("pictures/change_status", "PicturesController@change_status");
    Route::delete("pictures/delete/{id}", "PicturesController@destroy");

    Route::get("videos", "VideosController@index");
    Route::post("videos/change_status", "VideosController@change_status");
    Route::delete("videos/delete/{id}", "VideosController@destroy");
});

/***StoreManager***/

Route::get('/store', 'App\Http\Controllers\vendor\LoginController@login')->name('store.login');
//Route::get('/store', 'App\Http\Controllers\store\LoginController@login')->name('store.login');
Route::post('store/check_login', 'App\Http\Controllers\store\LoginController@check_login')->name('store.check_login');
Route::namespace('App\Http\Controllers\store')->prefix('store')->middleware('store')->name('store.')->group(function () {

    Route::get('change-password', 'AdminController@changePassword')->name('change.password');
    Route::post('change-password', 'AdminController@changePasswordSave')->name('change.password.save');
    Route::get('logout', 'LoginController@logout')->name('logout');
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::resource("vendors", "VendorsController");

    /***Stores***/
    Route::get("store", "StoreController@index");
    Route::get("store/create", "StoreController@create");
    Route::post("store/change_status", "StoreController@change_status");
    Route::post("store/verify", "StoreController@verify");
    Route::get("store/edit/{id}", "StoreController@edit");
    Route::delete("store/delete/{id}", "StoreController@destroy");
    Route::delete("store/delete_image/{id}", "StoreController@delete_image");
    Route::post("save_store", "StoreController@store");
    Route::post("store/get_by_vendor", "StoreController@get_by_vendor");

    Route::resource("store_manager", "StoreManagerController");

    /***StoreManagerProfile***/
    Route::get('my_profile', 'StoreManagerController@MyProfile');
});
