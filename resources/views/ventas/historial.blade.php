@extends('layouts.app')

@section('title', 'Historial de Ventas')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history me-2"></i>Historial de Ventas
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('ventas.pos') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Nueva Venta
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <form method="GET" class="row g-3">
                                    <div class="col-md-3">
                                        <input type="text" name="folio" class="form-control"
                                               placeholder="Buscar por folio" value="{{ request('folio') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="fecha_desde" class="form-control"
                                               value="{{ request('fecha_desde') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="fecha_hasta" class="form-control"
                                               value="{{ request('fecha_hasta') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="estado" class="form-select">
                                            <option value="">Todos los estados</option>
                                            <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completadas</option>
                                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                                            <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Canceladas</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i>Filtrar
                                        </button>
                                        <a href="{{ route('ventas.historial') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i>Limpiar
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Tabla de Ventas con Productos -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-dark text-white">
                                <tr>
                                    <th># Venta</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                    <th>Garantía</th>
                                    <th>Duración</th>
                                    <th>Empleado</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $contador = 1;
                                @endphp
                                @forelse($ventas as $venta)
                                    @php
                                        $numDetalles = $venta->detalles->count();
                                        $primerDetalle = true;
                                    @endphp

                                    @foreach($venta->detalles as $detalle)
                                        <tr>
                                            @if($primerDetalle)
                                                <td rowspan="{{ $numDetalles }}" class="align-middle text-center">
                                                <span class="badge bg-primary" style="font-size: 1rem; padding: 6px 12px;">
                                                    {{ $contador }}
                                                </span>
                                                    <br><small class="text-muted">Folio: {{ $venta->folio }}</small>
                                                </td>
                                                <td rowspan="{{ $numDetalles }}" class="align-middle text-center">
                                                    {{ $venta->created_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td rowspan="{{ $numDetalles }}" class="align-middle">
                                                    {{ $venta->cliente->nombre ?? 'Público' }}
                                                    @if($venta->cliente && $venta->cliente->telefono)
                                                        <br><small class="text-muted">Tel: {{ $venta->cliente->telefono }}</small>
                                                    @endif
                                                </td>
                                                @php $primerDetalle = false; @endphp
                                            @endif

                                            <td>
                                                <strong>{{ $detalle->producto->nombre ?? 'Producto no encontrado' }}</strong>
                                                @if($detalle->especificaciones)
                                                    <br><small class="text-muted">{{ Str::limit($detalle->especificaciones, 40) }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $detalle->cantidad }}</span>
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                ${{ number_format($detalle->subtotal, 2) }}
                                            </td>
                                            <td class="text-center">
                                                @if($detalle->garantia)
                                                    <span class="badge bg-success">Sí</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($detalle->duracion_garantia)
                                                    <span class="badge bg-info">{{ $detalle->duracion_garantia }}</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>

                                            @if($loop->first)
                                                <td rowspan="{{ $numDetalles }}" class="align-middle">
                                                    {{ $venta->usuario->name ?? 'N/A' }}
                                                </td>
                                                <td rowspan="{{ $numDetalles }}" class="align-middle text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('ventas.ticket', $venta->idventa) }}" class="btn btn-info btn-sm" target="_blank" title="Ver Ticket">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                        <a href="{{ route('ventas.edit', $venta->idventa) }}" class="btn btn-warning btn-sm" title="Editar Venta">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('ventas.destroy', $venta->idventa) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta venta?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar Venta">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    @php $contador++; @endphp
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-5">
                                            <i class="fas fa-shopping-cart fa-3x mb-3 d-block"></i>
                                            No hay ventas registradas
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                                <tfoot class="bg-light">
                                <tr>
                                    <td colspan="10" class="text-end">
                                        <strong>Total de ventas: {{ $ventas->total() }}</strong>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $ventas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .bg-dark {
            background-color: #2c3e50 !important;
        }
        .badge {
            font-size: 0.85rem;
            padding: 5px 10px;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        .text-success {
            color: #27ae60 !important;
            font-weight: 600;
        }
    </style>
@endpush
