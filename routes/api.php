<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\ServiceController;
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

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signUp']);
    Route::middleware(['auth:api'])->group(function () {
        Route::get('logout',  [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

Route::middleware(['auth:api'])->group(function () {
    Route::apiResources([
        'leads' => LeadController::class,
        'sellers' => SellerController::class,
        'quotes' => QuoteController::class,
    ]);

    Route::apiResource('services', ServiceController::class)->except([
        'show',
        'store',
        'delete',
        'update'
    ]);

});
