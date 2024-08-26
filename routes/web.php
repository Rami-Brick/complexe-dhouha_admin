<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-billing', function () {
    $fees = config('billing.fees');
    dd($fees);
});
