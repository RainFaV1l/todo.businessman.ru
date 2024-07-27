<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::controller(TaskController::class)->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->name('store');
    Route::delete('/{task}', 'destroy')->name('destroy');
    Route::post('/{task}/update', 'update')->name('update');
    Route::post('/{task}/accept', 'accept')->name('accept');
    Route::post('/{task}/reject', 'reject')->name('reject');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
