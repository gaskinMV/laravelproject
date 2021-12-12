<?php

use Illuminate\Support\Facades\Auth;
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
Auth::routes([
    'reset'=>false,
    'confirm'=>false,
    'verify'=>false,
]);


Route::get('/', 'App\Http\Controllers\MainController@index')->name('index');
Route::get('/product/{productName}', 'App\Http\Controllers\MainController@product');
Route::post('/basket/add/{id}','App\Http\Controllers\BasketController@basketAdd')->name('basket-add');
Route::group([
    'middleware' => 'basket_not_empty',
    'prefix' => 'basket'
],function()
{
    Route::get('/', 'App\Http\Controllers\BasketController@basket')->name('basket');
    Route::get('/place', 'App\Http\Controllers\BasketController@basketPlace')->name('basket-place');
    Route::post('/remove/{id}','App\Http\Controllers\BasketController@basketRemove')->name('basket-remove');
    Route::post('/fullRemove/{id}','App\Http\Controllers\BasketController@basketFullRemove')->name('basket-fullRemove');
    Route::post('/place','App\Http\Controllers\BasketController@basketConfirm')->name('basket-confirm');
});


Auth::routes();


Route::group(['middleware' => 'auth'], function(){
    Route::group(['middleware' => 'is_admin'], function (){
        Route::get('/home/users', [App\Http\Controllers\HomeController::class, 'users'])->name('users');
        Route::resource('users',\App\Http\Controllers\Auth\UserController::class );

    });
    Route::resource('orders',\App\Http\Controllers\Auth\OrderController::class );
    Route::resource('devices',\App\Http\Controllers\Auth\DeviceController::class );
    Route::get('/home/orders', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home/products', [App\Http\Controllers\HomeController::class, 'products'])->name('products');
});

