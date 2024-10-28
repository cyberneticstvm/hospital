<?php

use App\Http\Controllers\APIController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/mrecord')->controller(APIController::class)->group(function () {
    Route::get('/{id}/{secret}', 'getMrecord')->name('get.mrecord');
});
Route::prefix('/prescription')->controller(APIController::class)->group(function () {
    Route::get('/{id}/{secret}', 'getPrescription')->name('get.prescription');
});
Route::prefix('/customer')->controller(APIController::class)->group(function () {
    Route::get('/{val}/{secret}', 'getCustomer')->name('get.customer');
});
Route::prefix('/camp')->controller(APIController::class)->group(function () {
    Route::get('/order/{secret}', 'getCamps')->name('get.order.camps');
});
