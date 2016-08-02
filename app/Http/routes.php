<?php

Route::get('/', 'PopupController@showPopupsList');
Route::get('/popup', 'PopupController@showPopupsList');
Route::post('popup', 'PopupController@storePopup');
Route::get('popup/{popup}', 'PopupController@editPoup');
Route::get('popup/{popup}/shared/{secret}', 'PopupController@showSharedPopup');
Route::put('popup/{popup}', 'PopupController@updatePopup');
Route::delete('popup/{popup}', 'PopupController@destroyPopup');
Route::post('popup/{popup}/upload-image', 'PopupController@uploadImage');
Route::post('popup/{popup}/share', 'PopupController@sharePopup');

Route::get('profile', 'ProfileController@showProfile');
Route::put('profile', 'ProfileController@updateProfile');
Route::put('profile/password', 'ProfileController@updatePassword');

Route::resource('agency-account', 'AgencyAccountController', ['only' => [
    'index', 'store', 'destroy',
]]);
Route::resource('admin-account', 'AdminAccountController', ['only' => [
    'index', 'store', 'destroy',
]]);
Route::resource('customer-account', 'CustomerAccountController', ['only' => [
    'index', 'store', 'destroy', 'show', 'update',
]]);

Route::post('login-as/{user}', 'LoginAsController@loginAs');

//Route::auth();
Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');

APIRoute::version('v1', function () {
    ApiRoute::get('popup/{popup}', 'App\Http\Controllers\Api\PopupConfigController@getConfig');
});
