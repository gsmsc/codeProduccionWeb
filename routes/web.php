<?php

use App\Http\Controllers\EstilosController;
use App\Http\Controllers\LineasController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsuariosController;
use App\Http\Middleware\PuedeOperarUsuario;
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

    Route::controller(EstilosController::class)->group(function () {
        Route::get('/estilos/index', 'index')->name('estilos.index');
        Route::post('/estilos/store', 'store')->name('estilos.store');
        Route::get('/estilos/edit/{id}', 'edit')->name('estilos.edit');
        Route::put('/estilos/update/{id}', 'update')->name('estilos.update');
        Route::delete('/estilos/destroy/{id}', 'destroy')->name('estilos.destroy');
    });

    Route::controller(RolesController::class)->group(function () {
        Route::get('/roles/index', 'index')->name('roles.index');
        Route::get('/roles/create', 'create')->name('roles.create');
        Route::post('/roles/store', 'store')->name('roles.store');
        Route::get('/roles/edit/{id}', 'edit')->name('roles.edit');
        Route::delete('/roles/destroy/{id}', 'destroy')->name('roles.destroy');
        Route::put('/roles/update/{id}', 'update')->name('roles.update');
    });

    Route::controller(UsuariosController::class)->group(function () {
        Route::get('/usuarios/index', 'index')->name('usuarios.index');
        Route::get('/usuarios/create', 'create')->name('usuarios.create');
        Route::post('/usuarios/store', 'store')->name('usuarios.store');
        Route::get('/usuarios/edit/{idUsuario}', 'edit')->middleware(PuedeOperarUsuario::class)->name('usuarios.edit');
        Route::put('/usuarios/update/{idUsuario}', 'update')->middleware(PuedeOperarUsuario::class)->name('usuarios.update');
        Route::delete('/usuarios/destroy/{idUsuario}', 'destroy')->middleware(PuedeOperarUsuario::class)->name('usuarios.destroy');
        Route::get('/usuarios/configuracion', 'configuracion')->name('usuarios.configuracion');
        Route::put('/usuarios/actualizarDatosUsuario/{id}', 'actualizarDatosUsuario')->name('usuarios.actualizarDatosUsuario');
    });

    Route::controller(ProduccionController::class)->group(function () {
        Route::get('/produccion/index', 'index')->name('produccion.index');
        Route::get('/produccion/create', 'create')->name('produccion.create');
        Route::post('/produccion/store', 'store')->name('produccion.store');
        Route::get('/produccion/edit/{id}', 'edit')->name('produccion.edit');
        Route::put('/produccion/update/{id}', 'update')->name('produccion.update');
        Route::delete('/produccion/destroy/{id}', 'destroy')->name('produccion.destroy');
        Route::get('/produccion/listProductionUsers', 'listProductionUsers')->name('produccion.listProductionUsers');
        Route::get('/produccion/getProductionUsers/{id}', 'getProductionUsers')->name('produccion.getProductionUsers');
        Route::get('/produccion/xlsxPorSupervisor/{idUsuario}', 'xlsxPorSupervisor')->name('produccion.xlsxPorSupervisor');
        Route::post('/produccion/xlsxPorFecha', 'xlsxPorFecha')->name('produccion.xlsxPorFecha');
        Route::get('/produccion/pdfProduccion/{idProduccion}', 'pdfProduccion')->name('produccion.pdfProduccion');
    });
});
