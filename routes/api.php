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

    #--------------------------------##   <editor-fold desc="Customer Authenticate Routes">   ##--------------------------------------------#

    Route::post('register', 'RegisterController@register')->name('customer.register');
    Route::post('login', 'LoginController@login')->name('customer.login');
    Route::post('login/verify', 'LoginController@verify')->name('customer.login.verify');
    Route::post('logout', 'LoginController@logout')->name('customer.logout');
    Route::get('customer', 'CustomerController@index')->name('customer');

    # </editor-fold>

    #--------------------------------##   <editor-fold desc="Admin Authenticate Routes">   ##--------------------------------------------#

    Route::post('admin-login', 'AdminLoginController@login')->name('admin.login');
    Route::post('admin-logout', 'AdminLoginController@logout')->name('admin.logout');
    Route::get('admin', 'AdminController@index')->name('admin');

    # </editor-fold>

});


Route::apiResource('catalogs', 'CatalogsController')->except('show');
Route::apiResource('categories', 'CategoriesController')->except('show');
Route::apiResource('fields', 'FieldsController')->except('show');
Route::apiResource('categories.fields', 'CategoryFieldsController')->only('store');
Route::apiResource('portfolios', 'PortfoliosController');
Route::apiResource('tariffs', 'TariffsController');
Route::apiResource('tariffs.options', 'TariffOptionsController')->except('show');
Route::apiResource('orders', 'OrdersController')->except('show', 'delete', 'update');
Route::apiResource('order.receipts', 'OrderReceiptsController')->except('show');
Route::post('payments/verify', 'VerifyController@verify')->name('payments.verify');


Route::apiResource('pages', 'PagesController')->except('show', 'update');
Route::apiResource('widgets', 'WidgetsController')->except('show', 'index');
Route::apiResource('contexts', 'ContextsController')->except('show', 'index');
