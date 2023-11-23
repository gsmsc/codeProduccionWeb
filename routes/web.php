<?php

use App\Http\Controllers\LineasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('dashboard');

    Route::controller(LineasController::class)->group(function () {
        Route::get('/lineas/index', 'index')->name('lineas.index');
        Route::post('/lineas/store', 'store')->name('lineas.store');
        Route::put('/lineas/update', 'update')->name('lineas.update');
        Route::get('/lineas/getDataLinea/{id}', 'getDataLinea')->name('lineas.getDataLinea');
        Route::delete('/lineas/destroy/{id}', 'destroy')->name('lineas.destroy');
    });
});
