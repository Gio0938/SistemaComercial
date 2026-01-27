@extends('layouts.app')

@section('title', 'Dashboard Principal')

@section('content')
    <style>
        /* Solo añadí esta clase; quítala si quieres volver a mostrar los enlaces */
        .oculto { display: none !important; }
    </style>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Principal</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('servicios.create') }}" class="btn btn-sm btn-success {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                    <i class="fas fa-plus me-1"></i> Nuevo Servicio
                </a>
                <a href="{{ route('productos.create') }}" class="btn btn-sm btn-primary {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                    <i class="fas fa-plus me-1"></i> Nuevo Producto
                </a>
                <a href="{{ route('promociones.create') }}" class="btn btn-sm btn-warning {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                    <i class="fas fa-plus me-1"></i> Nueva Promoción
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas Principales -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card text-white bg-primary stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Servicios</div>
                            <div class="h2 mb-0">{{ $totalServicios }}</div>
                            <div class="mt-2">
                            <span class="badge bg-light text-primary">
                                <i class="fas fa-check-circle me-1"></i>{{ $serviciosActivos }} activos
                            </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-concierge-bell fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('servicios.index') }}">Ver detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card text-white bg-success stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Productos</div>
                            <div class="h2 mb-0">{{ $totalProductos }}</div>
                            <div class="mt-2">
                            <span class="badge bg-light text-success">
                                <i class="fas fa-boxes me-1"></i>{{ $productosEnStock }} en stock
                            </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('productos.index') }}">Ver detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card text-white bg-warning stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Promociones</div>
                            <div class="h2 mb-0">{{ $totalPromociones }}</div>
                            <div class="mt-2">
                            <span class="badge bg-light text-warning">
                                <i class="fas fa-bullhorn me-1"></i>{{ $promocionesActivas }} activas
                            </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('promociones.index') }}">Ver detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card text-white bg-info stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Stock Bajo</div>
                            <div class="h2 mb-0">{{ $productosStockBajo->count() }}</div>
                            <div class="mt-2">
                            <span class="badge bg-light text-info">
                                <i class="fas fa-exclamation-triangle me-1"></i>Necesita atención
                            </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('productos.index') }}?stock=bajo">Ver detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="row mt-4">
        <!-- Servicios Recientes -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-list me-2 text-primary"></i>Servicios Recientes</h5>
                    <a href="{{ route('servicios.create') }}" class="btn btn-sm btn-success {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                        <i class="fas fa-plus me-1"></i> Nuevo
                    </a>
                </div>
                <div class="card-body">
                    @if($serviciosRecientes->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($serviciosRecientes as $servicio)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-dark">{{ Str::limit($servicio->nombre, 30) }}</h6>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-dollar-sign me-1"></i>{{ number_format($servicio->precio, 2) }}
                                            •
                                            <i class="fas fa-clock me-1"></i>{{ $servicio->tipo_servicio }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                <span class="badge bg-{{ $servicio->disponible ? 'success' : 'danger' }} badge-status">
                                    {{ $servicio->disponible ? 'Activo' : 'Inactivo' }}
                                </span>
                                        <br>
                                        <small class="text-muted">{{ $servicio->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                            <p class="mb-2">No hay servicios registrados</p>
                            <a href="{{ route('servicios.create') }}" class="btn btn-sm btn-success {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                                <i class="fas fa-plus me-1"></i> Crear Primer Servicio
                            </a>
                        </div>
                    @endif
                </div>
                @if($serviciosRecientes->count() > 0)
                    <div class="card-footer bg-white">
                        <a href="{{ route('servicios.index') }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-list me-1"></i> Ver Todos los Servicios
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Productos Recientes -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-box me-2 text-success"></i>Productos Recientes</h5>
                    <a href="{{ route('productos.create') }}" class="btn btn-sm btn-success {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                        <i class="fas fa-plus me-1"></i> Nuevo
                    </a>
                </div>
                <div class="card-body">
                    @if($productosRecientes->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($productosRecientes as $producto)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-dark">{{ Str::limit($producto->nombre, 30) }}</h6>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-dollar-sign me-1"></i>{{ number_format($producto->precio, 2) }}
                                            •
                                            <i class="fas fa-cubes me-1"></i>Stock: {{ $producto->stock }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                <span class="badge bg-{{ $producto->disponible ? 'success' : 'danger' }} badge-status">
                                    {{ $producto->disponible ? 'Activo' : 'Inactivo' }}
                                </span>
                                        <br>
                                        <span class="badge bg-{{ $producto->stock > 10 ? 'success' : ($producto->stock > 0 ? 'warning' : 'danger') }} badge-status">
                                    {{ $producto->stock }} unidades
                                </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                            <p class="mb-2">No hay productos registrados</p>
                            <a href="{{ route('productos.create') }}" class="btn btn-sm btn-success {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                                <i class="fas fa-plus me-1"></i> Crear Primer Producto
                            </a>
                        </div>
                    @endif
                </div>
                @if($productosRecientes->count() > 0)
                    <div class="card-footer bg-white">
                        <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-success w-100">
                            <i class="fas fa-boxes me-1"></i> Ver Todos los Productos
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Promociones Recientes -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-tags me-2 text-warning"></i>Promociones Recientes</h5>
                    <a href="{{ route('promociones.create') }}" class="btn btn-sm btn-success {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                        <i class="fas fa-plus me-1"></i> Nueva
                    </a>
                </div>
                <div class="card-body">
                    @if($promocionesRecientes->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($promocionesRecientes as $promocion)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-dark">{{ Str::limit($promocion->nombre, 30) }}</h6>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-tag me-1"></i>{{ $promocion->tipo_promocion }}
                                            •
                                            <i class="fas fa-calendar me-1"></i>{{ $promocion->fecha_inicio->format('d/m') }} - {{ $promocion->fecha_fin->format('d/m') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        @php
                                            $estado = $promocion->estado;
                                            $badgeClass = [
                                                'activa' => 'bg-success',
                                                'programada' => 'bg-warning',
                                                'expirada' => 'bg-secondary',
                                                'inactiva' => 'bg-danger'
                                            ][$estado];
                                        @endphp
                                        <span class="badge {{ $badgeClass }} badge-status">
                                    {{ ucfirst($estado) }}
                                </span>
                                        <br>
                                        @if($promocion->limite_usos)
                                            <small class="text-muted">{{ $promocion->usos_actuales }}/{{ $promocion->limite_usos }}</small>
                                        @else
                                            <small class="text-muted">Ilimitado</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-tags fa-3x mb-3 opacity-50"></i>
                            <p class="mb-2">No hay promociones registradas</p>
                            <a href="{{ route('promociones.create') }}" class="btn btn-sm btn-success {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                                <i class="fas fa-plus me-1"></i> Crear Primera Promoción
                            </a>
                        </div>
                    @endif
                </div>
                @if($promocionesRecientes->count() > 0)
                    <div class="card-footer bg-white">
                        <a href="{{ route('promociones.index') }}" class="btn btn-sm btn-outline-warning w-100">
                            <i class="fas fa-tags me-1"></i> Ver Todas las Promociones
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Estadísticas Adicionales -->
    <div class="row mt-4">
        <!-- Estadísticas por Tipo de Servicio -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-pie me-2 text-info"></i>Servicios por Tipo</h5>
                </div>
                <div class="card-body">
                    @if($estadisticasServicios->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($estadisticasServicios as $estadistica)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <i class="fas fa-circle me-2 text-primary"></i>
                                        {{ $estadistica->tipo_servicio }}
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $estadistica->total }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-chart-pie fa-2x mb-3 opacity-50"></i>
                            <p>No hay datos disponibles</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alertas de Stock Bajo -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Alertas de Stock</h5>
                </div>
                <div class="card-body">
                    @if($productosStockBajo->count() > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>¡Atención!</strong> Tienes {{ $productosStockBajo->count() }} productos con stock bajo.
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach($productosStockBajo as $producto)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1 text-dark">{{ $producto->nombre }}</h6>
                                        <small class="text-muted">Stock actual: {{ $producto->stock }}</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-danger rounded-pill">Bajo</span>
                                        <a href="{{ route('productos.edit', $producto->idprod) }}" class="btn btn-sm btn-outline-primary ms-2 {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                            <p>Todo el stock está en niveles óptimos</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Estado de Promociones -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2 text-purple"></i>Estado de Promociones</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 bg-purple text-white">
                                <h3>{{ $promocionesPorEstado['activas'] }}</h3>
                                <p class="mb-0">Activas</p>
                                <small>En vigencia</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 bg-orange text-white">
                                <h3>{{ $promocionesPorEstado['programadas'] }}</h3>
                                <p class="mb-0">Programadas</p>
                                <small>Futuras</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 bg-pink text-white">
                                <h3>{{ $promocionesPorEstado['inactivas'] }}</h3>
                                <p class="mb-0">Inactivas</p>
                                <small>Pausadas</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 bg-secondary text-white">
                                <h3>{{ $promocionesPorEstado['expiradas'] }}</h3>
                                <p class="mb-0">Expiradas</p>
                                <small>Finalizadas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-bolt me-2 text-teal"></i>Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row quick-actions">
                        <div class="col-xl-2 col-md-4 col-6 text-center mb-3">
                            <a href="{{ route('servicios.create') }}" class="btn btn-outline-primary btn-lg w-100 py-3 h-100 d-flex flex-column justify-content-center {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <span>Nuevo Servicio</span>
                            </a>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6 text-center mb-3">
                            <a href="{{ route('productos.create') }}" class="btn btn-outline-success btn-lg w-100 py-3 h-100 d-flex flex-column justify-content-center {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                                <i class="fas fa-cube fa-2x mb-2"></i>
                                <span>Nuevo Producto</span>
                            </a>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6 text-center mb-3">
                            <a href="{{ route('promociones.create') }}" class="btn btn-outline-warning btn-lg w-100 py-3 h-100 d-flex flex-column justify-content-center {{ Auth::user()->rol !== 'admin' ? 'oculto' : '' }}">
                                <i class="fas fa-tag fa-2x mb-2"></i>
                                <span>Nueva Promoción</span>
                            </a>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6 text-center mb-3">
                            <a href="{{ route('servicios.index') }}" class="btn btn-outline-info btn-lg w-100 py-3 h-100 d-flex flex-column justify-content-center">
                                <i class="fas fa-list fa-2x mb-2"></i>
                                <span>Ver Servicios</span>
                            </a>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6 text-center mb-3">
                            <a href="{{ route('productos.index') }}" class="btn btn-outline-info btn-lg w-100 py-3 h-100 d-flex flex-column justify-content-center">
                                <i class="fas fa-boxes fa-2x mb-2"></i>
                                <span>Ver Productos</span>
                            </a>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6 text-center mb-3">
                            <a href="{{ route('promociones.index') }}" class="btn btn-outline-info btn-lg w-100 py-3 h-100 d-flex flex-column justify-content-center">
                                <i class="fas fa-tags fa-2x mb-2"></i>
                                <span>Ver Promociones</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Sistema -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-3">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i> Última actualización: {{ now()->format('d/m/Y H:i') }} |
                        <i class="fas fa-database me-1"></i> Total registros: {{ $totalServicios + $totalProductos + $totalPromociones }} |
                        <i class="fas fa-code me-1"></i> Sistema de Gestión Comercial v1.0
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .stat-card .card-footer {
            background: rgba(0,0,0,0.1);
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        .bg-purple { background: linear-gradient(45deg, #6f42c1, #8e63d2) !important; }
        .bg-orange { background: linear-gradient(45deg, #fd7e14, #ff9a4d) !important; }
        .bg-pink { background: linear-gradient(45deg, #e83e8c, #ed6ea9) !important; }
        .bg-teal { background: linear-gradient(45deg, #20c997, #3dd4af) !important; }
    </style>
@endpush
