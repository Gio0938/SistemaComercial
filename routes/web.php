<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;

// RUTAS PÚBLICAS (sin sesión)
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('/register', [RegisterController::class, 'formregister'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// RUTAS PROTEGIDAS (solo con sesión)
Route::middleware('auth')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('servicios', ServicioController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('promociones', PromocionController::class);

    Route::patch('/promociones/{promocione}/toggle',
        [PromocionController::class, 'toggle']
    )->name('promociones.toggle');

});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

