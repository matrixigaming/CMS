<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

/*Route::get('/', 'HomeController@index');
Route::post('/index/newsletter', 'HomeController@newsletter');
Route::get('/', function () {
    return view('welcome');
});

Route::get("/pricing", function(){
   return View::make("pricing.pricing");
});*/
Route::get('/clear', function() {
   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');
   //Artisan::call('route:cache');   
   return "Cleared!";
});
Route::get('/', function () {
   return redirect('login');
});
//Route::group(['middleware' => 'web'], function () {
//    Route::auth();
//
//    Route::get('/home', 'HomeController@index');
//});
Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/home', 'PageController@index');
   
    Route::get('/role_permission','RoleController@index');
    Route::get('/role_permission/type/{action}/{id}','RoleController@loadModal');
    Route::post('/role_permission/create', 'RoleController@create');
    Route::get('/role_permission/manage', 'RoleController@manage');
    Route::post('/role_permission/managePost', 'RoleController@managePost');
    Route::post('/permissions/create', 'PermissionController@create');
    Route::get('/user/list', 'UserController@userList');
    Route::post('/user/create', 'UserController@create');
    Route::get('/user/type/{action}/{id}','UserController@loadModal');
    
    Route::get('/user/profile','UserController@profile');
    Route::post('/user/profilePost', 'UserController@profilePost');
    Route::post('/user/avatarPost', 'UserController@avatarPost');
  
    
    Route::get('/user/add','UserController@add');
    Route::get('/user/edit/{id}','UserController@edit');
    Route::post('/user/addPost','UserController@addPost');
    Route::post('/user/changePasswordPost','UserController@changePasswordPost');
    
    Route::get('/states', 'StateController@index');
    Route::post('/states/create', 'StateController@create');
    Route::get('/states/type/{action}/{id}','StateController@loadModal');
    
    Route::get('/country/states/{id}','CountryController@getStates');
    Route::get('/common/type/{action}/{id}','CommonController@loadModal');
    Route::post('/common/upload_images', 'CommonController@store');
    Route::get('/common/delete_image/{module}/{id}','CommonController@deleteImageFromSession');
    Route::get('/common/delete_all_images/{module}','CommonController@deleteAllImageFromSession');
    Route::post('/common/upload_video', 'CommonController@vediodetails');
    Route::get('/common/delete_vedio/{module}/{id}','CommonController@deleteVedioFromSession');
    Route::post('/common/upload_files/','CommonController@fileDetails');
    Route::get('/common/delete_file/{module}/{id}','CommonController@deleteFileFromSession');
    Route::post('/common/add_open_house/','CommonController@openhousedetails');
    Route::post('/common/add_social_media/','CommonController@socialmediadetails');
    Route::get('/common/delete_socialmedia/{module}/{id}','CommonController@deleteSocialFromSession');
    Route::get('/common/delete_openhouse/{module}/{id}','CommonController@deleteOpenHouseFromSession');
    Route::post('/common/update', 'CommonController@update');


    
    Route::get('/state/cities/{id}','StateController@getCities');


    Route::get('/usersvideo/{id}','UserController@showVideo');
    
    
    Route::get('/user/delete_data/{type}/{id}','UserController@deletedata');
    
    Route::get('/games', 'GameController@index');
    Route::post('/games/create', 'GameController@create');
    Route::get('/games/type/{action}/{id}','GameController@loadModal');
    Route::post('/games/setrtp', 'GameController@setrtp');
    Route::get('/games/checkrtp/{shop_id}/{game_id}','GameController@checkrtp');
    
    
    Route::get('/distributor/list','UserController@distributorlist');
    Route::get('/distributor/add','UserController@distributoradd');
    Route::get('/distributor/edit/{id}','UserController@distributoredit');
    Route::post('/distributor/distributoraddPost','UserController@distributoraddPost');
    
    Route::get('/shop/list','UserController@shoplist');
    Route::get('/shop/add','UserController@shopadd');
    Route::get('/shop/edit/{id}','UserController@shopedit');
    Route::post('/shop/shopaddPost','UserController@shopaddPost');
    
    Route::get('/customer/list','CustomerController@clist');
    Route::get('/customer/type/{action}/{id}','CustomerController@loadModal');
    Route::post('/customer/create','CustomerController@create');
    Route::post('/customer/update_balance','CustomerController@update_balance');
    Route::post('/customer/generate_otp','CustomerController@generate_otp'); 
    Route::post('/customer/update_customer','CustomerController@update_customer');
    
    Route::get('/dashboard','UserController@dashboard');
    
    Route::get('/player','CustomerController@playerHomePage');
    Route::get('/player/login','CustomerController@playerLogin');
    Route::post('/player/loginPost','CustomerController@loginPost');
    Route::post('/player/ajaxLoginPost','CustomerController@ajaxLoginPost');
    Route::get('/player/game_list','CustomerController@game_list');
    //Route::post('/player/play','CustomerController@play');
    Route::match(['get', 'post'],'/player/play','CustomerController@play');
    Route::get('/player/logout','CustomerController@playerLogout');
    
    Route::post('/user/update_credit','UserController@update_credit');
    Route::post('/shop/set_tv_video','ShopVideoController@set_tv_video');
    Route::get('/shop_video/getShopVideo/{id}','ShopVideoController@getShopVideo');
    
    Route::match(['get', 'post'], '/reports/physical_money','ReportController@physical_money');
    Route::get('/distributor/shops/{id}','UserController@getDistributorShop');
    Route::match(['get', 'post'], '/reports/game_rtp','ReportController@game_rtp');
    Route::match(['get', 'post'], '/reports/jackpot_rtp','ReportController@jackpot_rtp');
    
    Route::get('/bounceback/settings','BouncebackSettingController@settings');
    Route::get('/bounceback/type/{action}/{id}','BouncebackSettingController@loadModal');
    Route::post('/bounceback/updatesettings','BouncebackSettingController@updatesettings');
    Route::post('/player/convert_win_credit','CustomerController@convert_win_credit');
    
    Route::post('/customer/adjust_transaction','CustomerController@adjust_transaction');
    Route::get('/shop/{id}','CustomerController@getCustomerListShopWise');
    Route::match(['get', 'post'], '/reports/transaction_log','ReportController@transaction_log');
    Route::match(['get', 'post'], '/reports/jackpot_history','ReportController@jackpot_history');
    Route::match(['get', 'post'], '/reports/game_history','ReportController@game_history');
    Route::match(['get', 'post'], '/reports/account_history','ReportController@account_history');
    
    Route::get('/loginverbiage', 'LoginVerbiageController@index');
    Route::post('/loginverbiage/create', 'LoginVerbiageController@create');
    Route::get('/loginverbiage/type/{action}/{id}','LoginVerbiageController@loadModal');

});


//Route::get('/player/game_list','CustomerController@game_list');

