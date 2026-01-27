@extends('layouts.app')

@section('title', 'Detalles del Producto')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-info-circle me-2"></i>Detalles del Producto</h1>

        <div class="btn-toolbar mb-2 mb-md-0">
            {{-- Solo mostrar Editar si es admin --}}
            @if(Auth::user()->esAdmin())
                <a href="{{ route('productos.edit', $producto->idprod) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i> Editar
                </a>
            @endif

            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información del Producto</h5>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información Básica</h6>
                            <p><strong>Nombre:</strong> {{ $producto->nombre }}</p>
                            <p><strong>Precio:</strong> ${{ number_format($producto->precio, 2) }}</p>
                            <p><strong>Categoría:</strong> {{ $producto->categoria ?? 'N/A' }}</p>
                            <p><strong>Proveedor:</strong> {{ $producto->proveedor ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6>Configuración</h6>
                            <p>
                                <strong>Marca:</strong>
                                @if($producto->marca)
                                    <span class="badge bg-primary">Marca Propia</span>
                                @else
                                    <span class="badge bg-secondary">Otra Marca</span>
                                @endif
                            </p>

                            <p>
                                <strong>Disponible:</strong>
                                @if($producto->disponible)
                                    <span class="badge bg-success">Sí</span>
                                @else
                                    <span class="badge bg-danger">No</span>
                                @endif
                            </p>

                            <p>
                                <strong>Stock:</strong>
                                <span class="badge bg-{{ $producto->stock > 0 ? 'success' : 'danger' }}">
                                    {{ $producto->stock }} unidades
                                </span>
                            </p>

                            <p><strong>Piezas:</strong> {{ $producto->numero_piezas ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($producto->descripcion)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Descripción</h6>
                                <p class="text-muted">{{ $producto->descripcion }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Especificaciones</h6>
                            <p><strong>Peso:</strong> {{ $producto->peso ? $producto->peso . ' kg' : 'N/A' }}</p>
                            <p><strong>Dimensiones:</strong> {{ $producto->dimensiones ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6>Información Adicional</h6>
                            <p><strong>Código de Barras:</strong> {{ $producto->codigo_barras ?? 'N/A' }}</p>
                            <p><strong>Creado:</strong> {{ $producto->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Actualizado:</strong> {{ $producto->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Foto SIEMPRE visible --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Foto del Producto</h5>
                </div>
                <div class="card-body text-center">
                    @if($producto->foto)
                        <img src="{{ Storage::url($producto->foto) }}" alt="Foto del producto"
                             class="img-fluid rounded" style="max-height: 300px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                            <div class="text-muted">
                                <i class="fas fa-image fa-3x mb-3"></i>
                                <p>Sin imagen</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ACCIONES RÁPIDAS SOLO PARA ADMIN --}}
            @if(Auth::user()->esAdmin())
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('productos.edit', $producto->idprod) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Editar Producto
                            </a>

                            <form action="{{ route('productos.destroy', $producto->idprod) }}" method="POST" class="d-grid">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                    <i class="fas fa-trash me-1"></i> Eliminar Producto
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
