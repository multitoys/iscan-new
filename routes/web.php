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

Route::middleware(['auth'])->group(function () {
    Route::get('client/search', 'ClientController@searchClients')->name('client.search');

    Route::resource('outsource', 'OutsourceController', ['only' => [
        'index', 'store', 'destroy'
    ]]);
    Route::resource('paper', 'PaperController', ['only' => [
        'index', 'store', 'destroy'
    ]]);
    Route::resource('service', 'ServiceController', ['only' => [
        'index', 'store', 'destroy'
    ]]);
    Route::resource('status', 'StatusController', ['only' => [
        'index', 'store', 'destroy'
    ]]);
    Route::resource('user', 'UserController', ['only' => [
        'index', 'store', 'destroy'
    ]]);

    Route::get('order/download/{order}/{file}', 'OrderController@downloadFile')->name('order.download_file');
    Route::delete('order/delete/{order}/{file}', 'OrderController@deleteFile')->name('order.delete_file');

    Route::get('/', 'OrderController@index')->name('order.index');
    Route::get('/order/design_report', 'OrderController@designReport')->name('order.design_report');
    Route::resource('order', 'OrderController', ['except' => [
        'index', 'show', 'show', 'store', 'destroy'
    ]]);
    Route::get('/entry_log', 'EntryLogController')->name('entry_log.index');
});
