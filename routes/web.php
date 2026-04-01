<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ReporteController;

// ==================== RUTAS PÚBLICAS (PÁGINA WEB) ====================
// Usamos /tienda para evitar conflicto con el CRUD
Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::get('/nosotros', [PublicController::class, 'nosotros'])->name('public.nosotros');
Route::get('/tienda/productos', [PublicController::class, 'productos'])->name('public.productos');
Route::get('/tienda/productos/{id}', [PublicController::class, 'productoDetalle'])->name('public.producto-detalle');
Route::get('/tienda/servicios', [PublicController::class, 'servicios'])->name('public.servicios');
Route::get('/tienda/servicios/{id}', [PublicController::class, 'servicioDetalle'])->name('public.servicio-detalle');
Route::get('/contacto', [PublicController::class, 'contacto'])->name('public.contacto');

// ==================== RUTAS DE AUTENTICACIÓN ====================
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('/register', [RegisterController::class, 'formregister'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ==================== RUTAS PROTEGIDAS (PANEL ADMIN) ====================
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD - Panel Administrativo (estas siguen igual, no se tocan)
    Route::resource('servicios', ServicioController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('promociones', PromocionController::class);
    Route::patch('/promociones/{promocione}/toggle', [PromocionController::class, 'toggle'])->name('promociones.toggle');

    // VENTAS - POS
    Route::get('/ventas/pos', [VentaController::class, 'create'])->name('ventas.pos');
    Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
    Route::get('/ventas/ticket/{id}', [VentaController::class, 'ticket'])->name('ventas.ticket');
    Route::get('/ventas/ticket-pdf/{id}', [VentaController::class, 'ticketPDF'])->name('ventas.ticket.pdf');
    Route::get('/ventas/historial', [VentaController::class, 'historial'])->name('ventas.historial');
    Route::get('/ventas/nuevo-folio', [VentaController::class, 'nuevoFolio'])->name('ventas.nuevo-folio');
    Route::get('/ventas/mis-ventas', [VentaController::class, 'misVentas'])->name('ventas.mis-ventas');

    // Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/servicios', [ReporteController::class, 'servicios'])->name('reportes.servicios');
    Route::get('/reportes/productos', [ReporteController::class, 'productos'])->name('reportes.productos');
    Route::get('/reportes/promociones', [ReporteController::class, 'promociones'])->name('reportes.promociones');
    Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');

    // Exportaciones PDF
    Route::get('/reportes/productos/pdf', [ReporteController::class, 'exportarProductosPDF'])->name('reportes.exportar-productos-pdf');
    Route::get('/reportes/ventas/pdf', [ReporteController::class, 'exportarVentasPDF'])->name('reportes.exportar-ventas-pdf');
    Route::get('/reportes/servicios/pdf', [ReporteController::class, 'exportarServiciosPDF'])->name('reportes.exportar-servicios-pdf');
    Route::get('/reportes/promociones/pdf', [ReporteController::class, 'exportarPromocionesPDF'])->name('reportes.exportar-promociones-pdf');
});
