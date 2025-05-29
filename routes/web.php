<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home.index');

    Route::get('/', [App\Http\Controllers\HomeController::class, 'Home'])
    ->name('home.index');

    Route::get('/forms-list', [App\Http\Controllers\Admin\FormController::class, 'index'])
    ->name('forms.index');

Route::get('/form/create', [App\Http\Controllers\Admin\FormController::class, 'create'])
    ->name('forms.create');
