<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\SlideController;
use App\Http\Controllers\Api\UploadController;
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
        Route::post('/login', [UserController::class, 'login'])->name('login');
        Route::post('/register', [UserController::class, 'register']);

        Route::get('/callback/{provider}', [UserController::class, 'callback']);
        Route::get('/redirect/{provider}', [UserController::class, 'redirect']);


        // Protected routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [UserController::class, 'logout']);
        });
    });

    Route::prefix('verify')->group(function () {
        // Send the email verification
        Route::post('/email/verification-notification', [UserController::class, 'sendVerificationEmail'])->middleware('throttle:6,1');
    });


    Route::prefix('admin')->group(function () {
        Route::post('/login', [AdminController::class, 'login']);
        Route::middleware(['admin.auth'])->group(function () {
            Route::prefix('slide')->group(function () {
                Route::post('/create', [SlideController::class, 'create']);
                Route::put('/update/{id}', [SlideController::class, 'update']);
                Route::delete('/delete/{id}', [SlideController::class, 'delete']);
            });

            Route::prefix('upload')->group(function () {
                Route::post('/create', [UploadController::class, 'create']);
                Route::put('/update/{id}', [UploadController::class, 'update']);
                Route::delete('/delete/{id}', [UploadController::class, 'delete']);
            });
        });
    });

    Route::prefix('slide')->group(function () {
        Route::get('/get', [SlideController::class, 'get']);
    });

    Route::prefix('upload')->group(function () {
        Route::get('/get', [UploadController::class, 'get']);
    });


});
