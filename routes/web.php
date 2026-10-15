<?php

use App\Http\Controllers\AprobacionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CobroController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\SaludoController;
use App\Http\Controllers\ViajeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/panel');

Route::get('/saludo', [SaludoController::class, 'saludo'])->name('saludo');

Route::get('/saludo/vista', [SaludoController::class, 'vista'])->name('saludo.vista');

/*
|--------------------------------------------------------------------------
| TrayectIA — vistas convertidas desde Stitch
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'autenticar'])->name('login.store');
    Route::post('/login/demo', [AuthController::class, 'demo'])->name('login.demo');
    Route::get('/registro', [AuthController::class, 'registro'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registrar'])->name('registro.store');
});

Route::get('/salida', [AuthController::class, 'salidaLista'])->name('salida.lista');

// Link público que el cliente recibe por WhatsApp para aprobar la recepción.
Route::get('/aprobar/{token}', [AprobacionController::class, 'show'])->name('aprobacion.cliente');
Route::post('/aprobar/{token}', [AprobacionController::class, 'aprobar'])->name('aprobacion.aprobar');

Route::middleware('auth')->group(function () {
    Route::get('/panel', PanelController::class)->name('panel');

    Route::get('/viajes', [ViajeController::class, 'index'])->name('viajes.index');
    Route::get('/viajes/nuevo', [ViajeController::class, 'create'])->name('viajes.create');
    Route::post('/viajes', [ViajeController::class, 'store'])->name('viajes.store');
    Route::get('/viajes/{viaje}', [ViajeController::class, 'show'])->name('viajes.show');
    Route::post('/viajes/{viaje}/avanzar', [ViajeController::class, 'avanzar'])->name('viajes.avanzar');

    Route::get('/aprobaciones', [AprobacionController::class, 'index'])->name('aprobaciones.index');

    Route::get('/cobros', CobroController::class)->name('cobros');

    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::put('/perfil/password', [PerfilController::class, 'updatePassword'])->name('perfil.password');

    Route::get('/cerrar-sesion',[AuthController::class, 'salir'])->name('logout.confirm');
    Route::post('/logout', [AuthController::class, 'cerrar'])->name('logout');
});
