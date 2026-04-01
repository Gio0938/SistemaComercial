@extends('layouts.app')

@section('title', 'Reporte de Productos')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-box me-2"></i>Reporte de Productos
                </h3>
                <div class="card-tools">
                    <a href="{{ route('reportes.exportar-productos-pdf') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                    </a>
                    <a href="{{ route('reportes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $estadisticas['total_productos'] }}</h3>
                                <p>Total Productos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $estadisticas['productos_stock_bajo'] }}</h3>
                                <p>Stock Bajo</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $estadisticas['productos_agotados'] }}</h3>
                                <p>Agotados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>${{ number_format($estadisticas['total_valor'], 2) }}</h3>
                                <p>Valor Inventario</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Productos -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                        </thead>
                        </thead>
                        <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td>{{ $producto->idprod }} </td>
                                <td>{{ $producto->nombre }} </td>
                                <td>{{ $producto->categoria ?? 'N/A' }} </td>
                                <td>${{ number_format($producto->precio, 2) }} </td>
                                <td class="{{ $producto->stock < 10 ? 'text-danger fw-bold' : '' }}">
                                    {{ $producto->stock }}
                                </td>
                                <td>
                                    @if($producto->stock == 0)
                                        <span class="badge bg-danger">Agotado</span>
                                    @elseif($producto->stock < 10)
                                        <span class="badge bg-warning">Stock Bajo</span>
                                    @else
                                        <span class="badge bg-success">Disponible</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay productos registrados</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
