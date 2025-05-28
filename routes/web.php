<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home.index');

    Route::get('/', [App\Http\Controllers\HomeController::class, 'Home'])
    ->name('home.index');
