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

Route::group(['middleware' => ['auth']], function(){
    Route::get('/dash', function () {
        return view('dash');
    });
    Route::get('/registration', function () {
        return view('user.registration');
    });
    
    Route::get('/logout', 'App\Http\Controllers\AuthController@userlogout');
    Route::post('/registration', 'App\Http\Controllers\AuthController@userregistration');
});

