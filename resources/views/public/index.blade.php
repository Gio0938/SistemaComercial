@extends('layouts.public')

@section('title', 'Inicio - Gestión Comercial')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1 class="fade-in-up">Bienvenido a TecnoShop</h1>
            <p class="fade-in-up">Tu mejor opción en productos y servicios de calidad</p>
            <a href="/tienda/productos" class="btn btn-primary btn-lg fade-in-up">
                <i class="fas fa-shopping-cart me-2"></i>Ver Productos
            </a>
            <a href="/tienda/servicios" class="btn btn-outline-light btn-lg fade-in-up ms-2">
                <i class="fas fa-concierge-bell me-2"></i>Ver Servicios
            </a>
        </div>
    </section>

    <!-- Promociones -->
    @if(isset($promocionesActivas) && $promocionesActivas->count() > 0)
        <section class="container py-5">
            <h2 class="text-center mb-5">Promociones Especiales</h2>
            <div class="row">
                @foreach($promocionesActivas as $promocion)
                    <div class="col-md-4 mb-4">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-tags fa-3x text-warning mb-3"></i>
                                <h5 class="card-title">{{ $promocion->nombre }}</h5>
                                <p class="card-text">{{ $promocion->descripcion }}</p>
                                @if($promocion->tipo_promocion == 'Porcentaje')
                                    <span class="badge bg-danger">{{ $promocion->descuento }}% OFF</span>
                                @elseif($promocion->tipo_promocion == 'Fijo')
                                    <span class="badge bg-danger">${{ number_format($promocion->descuento, 2) }} OFF</span>
                                @else
                                    <span class="badge bg-danger">{{ $promocion->tipo_promocion }}</span>
                                @endif
                                <p class="mt-2 text-muted">Válido hasta: {{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Productos Destacados -->
    @if(isset($productosDestacados) && $productosDestacados->count() > 0)
        <section class="container py-5 bg-light">
            <h2 class="text-center mb-5">Productos Destacados</h2>
            <div class="row">
                @foreach($productosDestacados as $producto)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            @if($producto->foto)
                                <img src="{{ asset('storage/' . $producto->foto) }}" class="card-img-top" alt="{{ $producto->nombre }}">
                            @else
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-box fa-3x text-white"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $producto->nombre }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($producto->descripcion, 80) }}</p>
                                <p class="card-price">${{ number_format($producto->precio, 2) }}</p>
                                <p><small class="text-muted">Stock: {{ $producto->stock }} unidades</small></p>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <a href="/tienda/productos/{{ $producto->idprod }}" class="btn btn-primary w-100">
                                    <i class="fas fa-info-circle me-2"></i>Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="/tienda/productos" class="btn btn-outline-primary">Ver todos los productos</a>
            </div>
        </section>
    @endif

    <!-- Servicios Destacados -->
    @if(isset($serviciosDestacados) && $serviciosDestacados->count() > 0)
        <section class="container py-5">
            <h2 class="text-center mb-5">Servicios Destacados</h2>
            <div class="row">
                @foreach($serviciosDestacados as $servicio)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            @if($servicio->foto)
                                <img src="{{ asset('storage/' . $servicio->foto) }}" class="card-img-top" alt="{{ $servicio->nombre }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-concierge-bell fa-3x text-white"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $servicio->nombre }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($servicio->descripcion, 80) }}</p>
                                <p class="card-price">${{ number_format($servicio->precio, 2) }}</p>
                                <p><small class="text-muted">Duración: {{ $servicio->duracion ? $servicio->duracion . ' hrs' : 'N/A' }}</small></p>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <a href="/tienda/servicios/{{ $servicio->idserv }}" class="btn btn-primary w-100">
                                    <i class="fas fa-info-circle me-2"></i>Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="/tienda/servicios" class="btn btn-outline-primary">Ver todos los servicios</a>
            </div>
        </section>
    @endif
@endsection
