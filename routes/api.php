<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\TransactionsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    // public routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',[AuthController::class, 'login']);
    //protected routes
    Route::middleware(['auth:sanctum'])->group(function() {

        Route::post('/logout',[AuthController::class, 'logout']);
        Route::resource('/transactions', TransactionsController::class);
        Route::resource('/payments', PaymentsController::class);
        Route::post('/report', [ReportsController::class,'monthly']);
    });
});

