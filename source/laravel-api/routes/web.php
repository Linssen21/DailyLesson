<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/helloworld', function () {
    return response()->json(['test' => 'Hello World']);
});

Route::get('/phpini', function () {
    return phpinfo();
});
