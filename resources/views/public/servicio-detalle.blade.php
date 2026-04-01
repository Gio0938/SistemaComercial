@extends('layouts.public')

@section('title', $servicio->nombre . ' - Gestión Comercial')

@section('content')
    <section class="container py-5">
        <div class="row">
            <div class="col-md-6">
                @if($servicio->foto)
                    <img src="{{ asset('storage/' . $servicio->foto) }}" class="img-fluid rounded" alt="{{ $servicio->nombre }}">
                @else
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                        <i class="fas fa-concierge-bell fa-5x text-white"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <h1>{{ $servicio->nombre }}</h1>
                <p class="text-muted">{{ $servicio->tipo_servicio }}</p>
                <h2 class="text-success">${{ number_format($servicio->precio, 2) }}</h2>
                <p><strong>Duración:</strong> {{ $servicio->duracion ? $servicio->duracion . ' horas' : 'N/A' }}</p>
                <p><strong>Personal requerido:</strong> {{ $servicio->personal_requerido ?? 'N/A' }}</p>

                @if($servicio->materiales_incluidos)
                    <p><strong>✅ Materiales incluidos</strong></p>
                @endif

                @if($servicio->garantia)
                    <p><strong>✅ Incluye garantía</strong></p>
                @endif

                <hr>
                <h4>Descripción del Servicio</h4>
                <p>{{ $servicio->descripcion }}</p>
                <a href="/tienda/servicios" class="btn btn-secondary mt-3">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Servicios
                </a>
            </div>
        </div>

        <!-- Servicios relacionados -->
        @if(isset($relacionados) && $relacionados->count() > 0)
            <div class="mt-5">
                <h3 class="mb-4">Servicios Relacionados</h3>
                <div class="row">
                    @foreach($relacionados as $relacionado)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100">
                                @if($relacionado->foto)
                                    <img src="{{ asset('storage/' . $relacionado->foto) }}" class="card-img-top" alt="{{ $relacionado->nombre }}" style="height: 150px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 150px;">
                                        <i class="fas fa-concierge-bell fa-2x text-white"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">{{ $relacionado->nombre }}</h6>
                                    <p class="card-price">${{ number_format($relacionado->precio, 2) }}</p>
                                </div>
                                <div class="card-footer bg-white border-0">
                                    <a href="/tienda/servicios/{{ $relacionado->idserv }}" class="btn btn-sm btn-outline-primary w-100">Ver más</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
