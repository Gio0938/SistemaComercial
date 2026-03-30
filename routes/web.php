<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VentaController;

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

// ==================== VENTAS - POS (TODAS LAS RUTAS JUNTAS) ====================
Route::get('/ventas/pos', [VentaController::class, 'create'])->name('ventas.pos');
Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
Route::get('/ventas/ticket/{id}', [VentaController::class, 'ticket'])->name('ventas.ticket');
Route::get('/ventas/ticket-pdf/{id}', [VentaController::class, 'ticketPDF'])->name('ventas.ticket.pdf');
Route::get('/ventas/historial', [VentaController::class, 'historial'])->name('ventas.historial');
Route::get('/ventas/nuevo-folio', [VentaController::class, 'nuevoFolio'])->name('ventas.nuevo-folio');
Route::get('/ventas/mis-ventas', [VentaController::class, 'misVentas'])->name('ventas.mis-ventas');
