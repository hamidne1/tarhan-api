<?php

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


Route::group(['namespace' => 'Authenticate'], function () {

    # Customer authenticate routes
    Route::post('register', 'RegisterController@register')->name('customer.register');
    Route::post('login', 'LoginController@login')->name('customer.login');
    Route::post('login/verify', 'LoginController@verify')->name('customer.login.verify');
    Route::post('logout', 'LoginController@logout')->name('customer.logout');
    Route::get('customer', 'CustomerController@index')->name('customer');

});
