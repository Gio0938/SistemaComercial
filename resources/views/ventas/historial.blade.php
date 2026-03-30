
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

                        <!-- Tabla de Ventas -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Empleado</th>
                                    <th>Subtotal</th>
                                    <th>IVA</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($ventas as $venta)
                                    <tr>
                                        <td>
                                            <strong>#{{ $venta->folio }}</strong>
                                        </td>
                                        <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $venta->cliente->nombre ?? 'Público' }}</td>
                                        <td>{{ $venta->usuario->name ?? 'N/A' }}</td>
                                        <td>${{ number_format($venta->subtotal, 2) }}</td>
                                        <td>${{ number_format($venta->iva, 2) }}</td>
                                        <td>${{ number_format($venta->total, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $venta->estado == 'completada' ? 'success' : ($venta->estado == 'pendiente' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($venta->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('ventas.ticket', $venta->idventa) }}"
                                                   class="btn btn-info btn-sm" target="_blank">
                                                    <i class="fas fa-print"></i> Ticket
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No hay ventas registradas</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-3">
                            {{ $ventas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
