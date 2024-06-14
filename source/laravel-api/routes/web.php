<?php

use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/email/verify/{id}/{hash}', [UserController::class, 'verificationEmail'])
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
});
