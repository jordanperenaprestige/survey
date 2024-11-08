<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::group(['prefix' => 'v1'], function () 
{
    /*
    |--------------------------------------------------------------------------
    | Auth Routes
    |--------------------------------------------------------------------------
    */    
    Route::post('/login', 'Api\AuthController@login')->name('api.v1.api.auth.login');
    Route::post('/register', 'Api\AuthController@register')->name('api.v1.api.auth.register');

    /*
    |--------------------------------------------------------------------------
    | Auth Password Reset
    |--------------------------------------------------------------------------
    */
    // Route::post('password/create', 'Admin\PasswordResetController@create')->name('api.v1.password.create');
    // Route::get('password/find/{token}', 'Admin\PasswordResetController@find')->name('api.v1.password.find');
    // Route::post('password/reset', 'Admin\PasswordResetController@reset')->name('api.v1.password.reset');

    /*
    |--------------------------------------------------------------------------
    | Main Kiosk Routes
    |--------------------------------------------------------------------------
    */  
    Route::get('/site', 'Kiosk\MainController@getSite')->name('kiosk.site');
    Route::get('/categories', 'Kiosk\MainController@getCategories')->name('kiosk.categories');
    // Route::get('/tenants/alphabetical/{id}', 'Kiosk\MainController@getTenantsAlphabetical')->where('id', '[0-9]+')->name('kiosk.tenants');
    Route::get('/tenants/category/{id}', 'Kiosk\MainController@getTenantsByCategory')->where('id', '[0-9]+')->name('kiosk.tenants.by-category');
    Route::get('/tenants/supplemental/{id}', 'Kiosk\MainController@getTenantsBySupplementals')->where('id', '[0-9]+')->name('kiosk.tenants.by-supplemental');
    Route::get('/tenants/suggestion/list', 'Kiosk\MainController@getSuggestionList')->where('id', '[0-9]+')->name('kiosk.tenants.suggestion');
    Route::post('/search', 'Kiosk\MainController@search')->name('kiosk.search');
    Route::get('/get-like-count/{id}', 'Kiosk\MainController@getLikeCount')->name('kiosk.get-like-count');
    Route::post('/like-count', 'Kiosk\MainController@putLikeCount')->name('kiosk.like-count');
    Route::post('/feedback', 'Kiosk\MainController@putFeedback')->name('kiosk.feedback');
    Route::get('/assistant-message', 'Kiosk\MainController@getAssistantMessage')->name('kiosk.assistant-message');
    Route::get('/translation', 'Kiosk\MainController@getTranslation')->name('kiosk.translation');

    Route::get('/advertisements/banners', 'Kiosk\MainController@getBanners')->name('kiosk.banners');
    Route::get('/advertisements/fullscreen', 'Kiosk\MainController@getFullscreen')->name('kiosk.fullscreen');
   
    Route::get('/promos', 'Kiosk\MainController@getPromos')->name('kiosk.promos');
    
    Route::get('/cinemas', 'Kiosk\MainController@getCinemas')->name('kiosk.cinemas');
    Route::get('/now-showing', 'Kiosk\MainController@getShowing')->name('kiosk.now-showing');
    Route::get('/tenants/all', 'Kiosk\MainController@getAllTenants')->where('id', '[0-9]+')->name('kiosk.tenants.all');
    Route::get('/site/floors', 'Kiosk\MainController@getFloors')->where('id', '[0-9]+')->name('kiosk.site.floors');
    Route::get('/site/maps', 'Kiosk\MainController@getMaps')->where('id', '[0-9]+')->name('kiosk.site.maps');
    Route::get('/site/maps/get-points/{id}', 'Kiosk\MainController@getPoints')->where('id', '[0-9]+')->name('kiosk.site.get-points');
    Route::get('/site/maps/get-routes/{id}', 'Kiosk\MainController@getRoutes')->where('id', '[0-9]+')->name('kiosk.site.get-routes');
    Route::get('/site/maps/get-floor-name/{id}', 'Kiosk\MainController@getFloorName')->where('id', '[0-9]+')->name('kiosk.site.get-floor-name');
    Route::get('/site/maps/get-building-name/{id}', 'Kiosk\MainController@getBuildingName')->where('id', '[0-9]+')->name('kiosk.site.get-building-name');
    Route::get('/site/maps/get-map-id/{level_id}/{buidlind_id}', 'Kiosk\MainController@getFloorMap')->where('level_id', '[0-9]+')->where('buidlind_id', '[0-9]+')->name('kiosk.site.get-map-id');

    Route::get('/employee/get-concerns', 'Kiosk\MainController@getConcerns')->name('kiosk.employee.concerns');
    Route::get('/employee/get-answer-details/{id}', 'Kiosk\MainController@getAnswerDetails')->where('id', '[0-9]+')->name('kiosk.employee.concern.details');
    Route::get('/employee/get-rooms', 'Kiosk\MainController@getRooms')->name('kiosk.employee.rooms');
    Route::get('/employee/get-default-room/{id}', 'Kiosk\MainController@getDefaultRoom')->where('id', '[0-9]+')->name('kiosk.employee.default.room');
    Route::get('/employee/get-buildings', 'Kiosk\MainController@getBuildings')->name('kiosk.employee.buildings');
    Route::post('/employee/store-concern', 'Kiosk\MainController@storeConcern')->name('kiosk.employee.store-concern');
    Route::post('/employee/store-concern-pending', 'Kiosk\MainController@storeConcernPending')->name('kiosk.employee.store-concern-pending');
    Route::get('/employee/get-sms', 'Kiosk\MainController@getSMS')->name('kiosk.employee.sms');
    Route::get('/employee/get-building-details', 'Kiosk\MainController@getBuildingDetails')->name('kiosk.building.details');
    Route::get('/employee/get-survey/{id}', 'Kiosk\MainController@getSurvey')->where('id', '[0-9]+')->name('kiosk.employee.survey');
    Route::get('/employee/get-room-survey/{id}', 'Kiosk\MainController@getRoomSurvey')->where('id', '[0-9]+')->name('kiosk.employee.room.survey');
    Route::get('/employee/get-answer-details/{id}', 'Kiosk\MainController@getAnswerDetails')->where('id', '[0-9]+')->name('kiosk.employee.concern.details');
    Route::post('employee/update-status', 'Kiosk\MainController@updateStatus')->name('kiosk.employee.update-status');
    Route::post('/employee/local-login', 'Kiosk\MainController@localLogin')->name('kiosk.employee.local-login');
    Route::post('/employee/switch-room', 'Kiosk\MainController@switchRoom')->name('kiosk.employee.switch-room');
   
    

    /*
    |--------------------------------------------------------------------------
    | Get Update 
    |--------------------------------------------------------------------------
    */
    // Route::get('/get-update', 'Api\GetUpdateController@updateContent')->name('api.get-update');
    Route::post('/save-logs', 'Api\LogsController@storeLogs')->name('api.save-logs');
    Route::post('/screen-uptime', 'Api\UpTimeController@storeUpTime')->name('api.screen-uptime');
    /*
    |--------------------------------------------------------------------------
    | Get Update Meeting Room
    |--------------------------------------------------------------------------
    */
    //Route::get('/get-update-meeting-room', 'Api\GetUpdateController@updateContent')->name('api.get-update');

    Route::get('/employee/site/get-all', 'Kiosk\MainController@getAll')->where('id', '[0-9]+')->name('employee.site.get-all');
    
    
    Route::get('/employee/site/get-buildings', 'Kiosk\MainController@getBuildingss')->where('id', '[0-9]+')->name('employee.site.buildings.get-buildings');
    Route::get('/employee/site/floors', 'Kiosk\MainController@getFloors')->where('id', '[0-9]+')->name('employee.site.floors');
    Route::get('/employee/site/floors/rooms', 'Kiosk\MainController@getRoomss')->where('id', '[0-9]+')->name('employee.site.floors.roomss');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
