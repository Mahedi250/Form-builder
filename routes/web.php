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

    Route::post('/form/store', [App\Http\Controllers\Admin\FormController::class, 'store'])
    ->name('forms.store');
    Route::post('/form/delete/{id}', [App\Http\Controllers\Admin\FormController::class, 'destroy'])
    ->name('forms.destroy');
// Route::get('/form/{form}/edit', [App\Http\Controllers\Admin\FormController::class, 'edit'])
//     ->name('forms.edit');
// Route::put('/form/{form}', [App\Http\Controllers\Admin\FormController::class, 'update'])
//     ->name('forms.update');

// Route::get('/form/{form}/fields', [App\Http\Controllers\Admin\FormFieldController::class, 'index'])
//     ->name('forms.fields.index');
