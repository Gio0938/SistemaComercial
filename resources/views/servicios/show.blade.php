@extends('layouts.app')

@section('title', 'Detalles del Servicio')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-info-circle me-2"></i>Detalles del Servicio</h1>

        <div class="btn-toolbar mb-2 mb-md-0">

            {{-- SOLO ADMIN PUEDE EDITAR --}}
            @if(auth()->user()->rol === 'admin')
                <a href="{{ route('servicios.edit', $servicio->idserv) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i> Editar
                </a>
            @endif

            <a href="{{ route('servicios.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        {{-- COLUMNA PRINCIPAL --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información del Servicio</h5>
                </div>

                <div class="card-body">
                    <div class="row">

                        {{-- INFORMACIÓN BÁSICA --}}
                        <div class="col-md-6">
                            <h6>Información Básica</h6>
                            <p><strong>Nombre:</strong> {{ $servicio->nombre }}</p>
                            <p><strong>Precio:</strong> ${{ number_format($servicio->precio, 2) }}</p>
                            <p><strong>Categoría:</strong> {{ $servicio->categoria ?? 'N/A' }}</p>

                            <p><strong>Tipo:</strong>
                                <span class="badge bg-info">{{ $servicio->tipo_servicio }}</span>
                            </p>
                        </div>

                        {{-- CONFIGURACIÓN --}}
                        <div class="col-md-6">
                            <h6>Configuración</h6>

                            {{-- Disponible --}}
                            <p>
                                <strong>Disponible:</strong>
                                @if($servicio->disponible)
                                    <span class="badge bg-success">Sí</span>
                                @else
                                    <span class="badge bg-danger">No</span>
                                @endif
                            </p>

                            {{-- Materiales --}}
                            <p>
                                <strong>Materiales Incluidos:</strong>
                                @if($servicio->materiales_incluidos)
                                    <span class="badge bg-success">Sí</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </p>

                            {{-- Garantía --}}
                            <p>
                                <strong>Garantía:</strong>
                                @if($servicio->garantia)
                                    <span class="badge bg-success">Sí</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </p>

                            {{-- Duración --}}
                            <p>
                                <strong>Duración:</strong>
                                {{ $servicio->duracion ? $servicio->duracion . ' horas' : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    {{-- DESCRIPCIÓN --}}
                    @if($servicio->descripcion)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Descripción</h6>
                                <p class="text-muted">{{ $servicio->descripcion }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- INFORMACIÓN EXTRA --}}
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Recursos</h6>
                            <p><strong>Personal Requerido:</strong> {{ $servicio->personal_requerido ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6>Información Adicional</h6>
                            <p><strong>Creado:</strong> {{ $servicio->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Actualizado:</strong> {{ $servicio->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA SECUNDARIA --}}
        <div class="col-md-4">

            {{-- FOTO DEL SERVICIO --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Foto del Servicio</h5>
                </div>

                <div class="card-body text-center">
                    @if($servicio->foto)
                        <img src="{{ Storage::url($servicio->foto) }}"
                             alt="Foto del servicio"
                             class="img-fluid rounded"
                             style="max-height: 300px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                             style="height: 200px;">
                            <div class="text-muted text-center">
                                <i class="fas fa-image fa-3x mb-3"></i>
                                <p>Sin imagen</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ACCIONES RÁPIDAS --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Acciones Rápidas</h5>
                </div>

                <div class="card-body">
                    <div class="d-grid gap-2">

                        {{-- EDITAR (ADMIN) --}}
                        @if(auth()->user()->rol === 'admin')
                            <a href="{{ route('servicios.edit', $servicio->idserv) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Editar Servicio
                            </a>
                        @endif

                        {{-- ELIMINAR (ADMIN) --}}
                        @if(auth()->user()->rol === 'admin')
                            <form action="{{ route('servicios.destroy', $servicio->idserv) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger"
                                        onclick="return confirm('¿Estás seguro de eliminar este servicio?')">
                                    <i class="fas fa-trash me-1"></i> Eliminar Servicio
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
