<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v2')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/login', [UserController::class, 'login']);
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::prefix('verify')->group(function () {
        // Send the email verification
        Route::post('/email/verification-notification', [UserController::class, 'sendVerificationEmail'])->middleware('throttle:6,1');
    });


    Route::prefix('admin')->group(function () {
        Route::post('/login', [AdminController::class, 'login']);
    });

});
