@extends('layouts.app')

@section('title', 'Detalles de la Promoción')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-info-circle me-2"></i>Detalles de la Promoción</h1>
        <div class="btn-toolbar mb-2 mb-md-0">

            {{-- SOLO ADMIN VE EDITAR --}}
            @if(Auth::user()->rol === 'admin')
                <a href="{{ route('promociones.edit', $promocione->idpromo) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i> Editar
                </a>
            @endif

            <a href="{{ route('promociones.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información de la Promoción</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información Básica</h6>
                            <p><strong>Nombre:</strong> {{ $promocione->nombre }}</p>
                            <p><strong>Tipo:</strong> <span class="badge bg-info">{{ $promocione->tipo_promocion }}</span></p>
                            <p><strong>Código:</strong> {{ $promocione->codigo_promocion ?? 'N/A' }}</p>
                            <p><strong>Estado:</strong>
                                @php
                                    $estado = $promocione->estado;
                                    $badgeClass = [
                                        'activa' => 'bg-success',
                                        'programada' => 'bg-warning',
                                        'expirada' => 'bg-secondary',
                                        'inactiva' => 'bg-danger'
                                    ][$estado];
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($estado) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Detalles de la Promoción</h6>
                            <p>
                                <strong>Descuento:</strong>
                                @if($promocione->tipo_promocion == 'Porcentaje')
                                    {{ $promocione->descuento }}%
                                @elseif($promocione->tipo_promocion == 'Fijo')
                                    ${{ number_format($promocione->descuento, 2) }}
                                @elseif($promocione->precio_promocional)
                                    ${{ number_format($promocione->precio_promocional, 2) }}
                                @else
                                    {{ $promocione->tipo_promocion }}
                                @endif
                            </p>
                            <p><strong>Límite de usos:</strong> {{ $promocione->limite_usos ?? 'Ilimitado' }}</p>
                            <p><strong>Usos actuales:</strong> {{ $promocione->usos_actuales }}</p>
                            @if($promocione->dias_restantes > 0 && $estado == 'activa')
                                <p><strong>Días restantes:</strong> {{ $promocione->dias_restantes }} días</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Vigencia</h6>
                            <p><strong>Fecha de inicio:</strong> {{ $promocione->fecha_inicio->format('d/m/Y') }}</p>
                            <p><strong>Fecha de fin:</strong> {{ $promocione->fecha_fin->format('d/m/Y') }}</p>
                            <p><strong>Duración:</strong> {{ $promocione->fecha_inicio->diffInDays($promocione->fecha_fin) }} días</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Aplicación</h6>
                            @if($promocione->aplica_todos_servicios)
                                <p><strong>Aplica a:</strong> <span class="badge bg-primary">Todos los Servicios</span></p>
                            @elseif($promocione->servicio)
                                <p><strong>Servicio:</strong> {{ $promocione->servicio->nombre }}</p>
                                <p><strong>Precio original:</strong> ${{ number_format($promocione->servicio->precio, 2) }}</p>
                            @elseif($promocione->aplica_todos_productos)
                                <p><strong>Aplica a:</strong> <span class="badge bg-primary">Todos los Productos</span></p>
                            @elseif($promocione->producto)
                                <p><strong>Producto:</strong> {{ $promocione->producto->nombre }}</p>
                                <p><strong>Precio original:</strong> ${{ number_format($promocione->producto->precio, 2) }}</p>
                            @else
                                <p><strong>Aplicación:</strong> No especificada</p>
                            @endif
                        </div>
                    </div>

                    @if($promocione->descripcion)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Descripción</h6>
                                <p class="text-muted">{{ $promocione->descripcion }}</p>
                            </div>
                        </div>
                    @endif

                    @if($promocione->condiciones)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Condiciones Especiales</h6>
                                <p class="text-muted">{{ $promocione->condiciones }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Información Adicional</h6>
                            <p><strong>Creado:</strong> {{ $promocione->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Actualizado:</strong> {{ $promocione->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Resumen de la Promoción</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @php
                            $iconClass = [
                                'Porcentaje' => 'fa-percent',
                                'Fijo' => 'fa-money-bill-wave',
                                '2x1' => 'fa-gift',
                                '3x2' => 'fa-gifts',
                                'Envio Gratis' => 'fa-shipping-fast'
                            ][$promocione->tipo_promocion];
                        @endphp
                        <i class="fas {{ $iconClass }} fa-4x text-primary mb-3"></i>
                        <h4>{{ $promocione->nombre }}</h4>
                        <p class="text-muted">{{ $promocione->tipo_promocion }}</p>
                    </div>

                    <div class="d-grid gap-2">

                        {{-- SOLO ADMIN VE ESTOS BOTONES --}}
                        @if(Auth::user()->rol === 'admin')

                            <a href="{{ route('promociones.edit', $promocione->idpromo) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Editar Promoción
                            </a>

                            <form action="{{ route('promociones.toggle', $promocione->idpromo) }}" method="POST" class="d-grid">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-{{ $promocione->activa ? 'secondary' : 'success' }}">
                                    <i class="fas fa-{{ $promocione->activa ? 'pause' : 'play' }} me-1"></i>
                                    {{ $promocione->activa ? 'Desactivar' : 'Activar' }} Promoción
                                </button>
                            </form>

                            <form action="{{ route('promociones.destroy', $promocione->idpromo) }}" method="POST" class="d-grid">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta promoción?')">
                                    <i class="fas fa-trash me-1"></i> Eliminar Promoción
                                </button>
                            </form>

                        @endif

                    </div>
                </div>
            </div>

            @if($promocione->servicio)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Servicio Relacionado</h5>
                    </div>
                    <div class="card-body">
                        <h6>{{ $promocione->servicio->nombre }}</h6>
                        <p class="text-muted">{{ Str::limit($promocione->servicio->descripcion, 100) }}</p>
                        <p><strong>Precio:</strong> ${{ number_format($promocione->servicio->precio, 2) }}</p>
                        <a href="{{ route('servicios.show', $promocione->servicio->idserv) }}" class="btn btn-sm btn-outline-primary">
                            Ver Servicio
                        </a>
                    </div>
                </div>
            @endif

            @if($promocione->producto)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Producto Relacionado</h5>
                    </div>
                    <div class="card-body">
                        <h6>{{ $promocione->producto->nombre }}</h6>
                        <p class="text-muted">{{ Str::limit($promocione->producto->descripcion, 100) }}</p>
                        <p><strong>Precio:</strong> ${{ number_format($promocione->producto->precio, 2) }}</p>
                        <p><strong>Stock:</strong> {{ $promocione->producto->stock }}</p>
                        <a href="{{ route('productos.show', $promocione->producto->idprod) }}" class="btn btn-sm btn-outline-primary">
                            Ver Producto
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
