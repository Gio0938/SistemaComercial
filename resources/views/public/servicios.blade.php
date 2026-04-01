@extends('layouts.public')

@section('title', 'Servicios - Gestión Comercial')

@section('content')
    <section class="container py-5">
        <h1 class="text-center mb-4">Nuestros Servicios</h1>
        <p class="text-center text-muted mb-5">Ofrecemos servicios profesionales de alta calidad</p>

        <!-- Filtros y búsqueda -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="d-flex flex-wrap">
                    <a href="/tienda/servicios" class="category-badge {{ !request('tipo') ? 'bg-primary text-white' : '' }}">
                        Todos
                    </a>
                    @foreach($tipos as $tipo)
                        <a href="/tienda/servicios?tipo={{ $tipo }}"
                           class="category-badge {{ request('tipo') == $tipo ? 'bg-primary text-white' : '' }}">
                            {{ $tipo }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <form method="GET" action="/tienda/servicios">
                    <div class="input-group">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar servicios..." value="{{ request('buscar') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    @if(request('tipo'))
                        <input type="hidden" name="tipo" value="{{ request('tipo') }}">
                    @endif
                </form>
            </div>
        </div>

        <!-- Lista de servicios -->
        <div class="row">
            @forelse($servicios as $servicio)
                <div class="col-md-4 mb-4">
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
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-concierge-bell fa-4x text-muted mb-3"></i>
                    <h3>No se encontraron servicios</h3>
                    <p>Prueba con otros filtros o categorías.</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $servicios->links() }}
        </div>
    </section>
@endsection
