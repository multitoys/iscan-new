<?php

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

Auth::routes();

Route::get('/', 'MainController@index')->name('main');

Route::middleware(['auth'])->group(function () {
    Route::get('client/search', 'ClientController@searchClients')->name('client.search');
    
    Route::resource('outsource', 'OutsourceController', ['only' => [
        'index', 'update', 'destroy'
    ]]);
    Route::resource('paper', 'PaperController', ['only' => [
        'index', 'update', 'destroy'
    ]]);
    Route::resource('service', 'ServiceController', ['only' => [
        'index', 'update', 'destroy'
    ]]);
    Route::resource('status', 'StatusController', ['only' => [
        'index', 'update', 'destroy'
    ]]);
    Route::resource('user', 'UserController', ['only' => [
        'index', 'update', 'destroy'
    ]]);

    Route::get('order/download/{order}/{file}', 'OrderController@downloadFile')->name('order.download_file');
    Route::delete('order/delete/{order}/{file}', 'OrderController@deleteFile')->name('order.delete_file');
    Route::resource('order', 'OrderController', ['except' => [
        'show', 'store', 'destroy'
    ]]);
});