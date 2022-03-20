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


Route::get('/', function () {
    return view('/login');
})->name('login');
Route::post('/', 'App\Http\Controllers\AuthController@userlogin')->name('login');
Route::post('/roles/create/', 'App\Http\Controllers\RoleController@store');

Route::group(['middleware' => ['auth']], function(){
    Route::get('/dash/', function () {
        return view('dash');
    })->name('dash');

    Route::get('/permission/not-authorized/', function () {
        return view('permission');
    })->name('notauth');

    Route::get('/logout/', 'App\Http\Controllers\AuthController@userlogout');

    // User Route //
    Route::get('/user/', 'App\Http\Controllers\AuthController@index')->name('user.index');
    Route::post('/user/create/', 'App\Http\Controllers\AuthController@store');
    Route::get('/user/create/', 'App\Http\Controllers\AuthController@show');
    Route::get('/user/{id}/edit/', 'App\Http\Controllers\AuthController@edit')->name('user.edit');
    Route::put('/user/{id}/edit/', 'App\Http\Controllers\AuthController@update')->name('user.update');
    Route::delete('/user/{id}/delete/', 'App\Http\Controllers\AuthController@destroy')->name('user.delete');
    // End User Route //

    // Role Route //
    Route::get('/roles/', 'App\Http\Controllers\RoleController@index')->name('roles.index');
    Route::get('/roles/create/', 'App\Http\Controllers\RoleController@show');
    //Route::post('/roles/create/', 'App\Http\Controllers\RoleController@store');
    Route::get('/roles/{id}/edit/', 'App\Http\Controllers\RoleController@edit');
    Route::put('/roles/{id}/edit/', 'App\Http\Controllers\RoleController@update');
    Route::delete('/roles/{id}/delete/', 'App\Http\Controllers\RoleController@destroy');
    // End Role Route //
});

