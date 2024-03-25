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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', [App\Http\Controllers\FrontEndController::class, 'index']);
Route::get('/search', [App\Http\Controllers\FrontEndController::class, 'search']);

Route::get('/Prescriptions', [App\Http\Controllers\FrontEndController::class, 'showSimilarPrescriptions'])->name('showSimilarPrescriptions');
Route::get('/recommendDoctor', [App\Http\Controllers\FrontEndController::class, 'recommendDoctor']);
