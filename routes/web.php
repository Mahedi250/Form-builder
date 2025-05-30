<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;



// Authentication routes
Route::get('/login', [LoginController::class, 'show'])->name('login'); // <-- add this line
Route::get('/', function () {
    return redirect()->route('login');
});
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin-only routes for forms
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
   
    Route::get('/home', [App\Http\Controllers\HomeController::class,'home'])->name('Home');
    Route::get('/forms-list', [App\Http\Controllers\Admin\FormController::class, 'index'])
        ->name('forms.index');
    Route::get('/form/create', [App\Http\Controllers\Admin\FormController::class, 'create'])
        ->name('forms.create');
    Route::post('/form/store', [App\Http\Controllers\Admin\FormController::class, 'store'])
        ->name('forms.store');
    Route::get('/form/edit/{id}', [App\Http\Controllers\Admin\FormController::class, 'edit'])
        ->name('forms.edit');
    Route::put('/form/{form}', [App\Http\Controllers\Admin\FormController::class, 'update'])
        ->name('forms.update');
    Route::post('/form/delete/{id}', [App\Http\Controllers\Admin\FormController::class, 'destroy'])
        ->name('forms.destroy');
});
