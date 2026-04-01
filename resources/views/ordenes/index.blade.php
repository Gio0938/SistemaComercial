@extends('layouts.app')

@section('title', 'Gestión de Servicios Técnicos')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-list me-2"></i>Órdenes de Servicio
                </h3>
                <div class="card-tools">
                    <a href="{{ route('ordenes.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Nueva Orden
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-dark text-white">
                        60
                        <th class="text-center">N° Actividades</th>
                        <th class="text-center">Fecha</th>
                        <th>Técnico</th>
                        <th>Cliente</th>
                        <th>Equipo</th>
                        <th class="text-center">Total Preventivo</th>
                        <th class="text-center">Total Correctivo</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                        </thead>
                        </thead>
                        <tbody>
                        @forelse($ordenes as $orden)
                            @php
                                // Calcular totales por tipo de servicio
                                $totalPreventivo = 0;
                                $totalCorrectivo = 0;
                                $numActividades = $orden->detalles->count();

                                foreach($orden->detalles as $detalle) {
                                    if($detalle->tipo == 'preventivo') {
                                        $totalPreventivo += $detalle->subtotal;
                                    } else {
                                        $totalCorrectivo += $detalle->subtotal;
                                    }
                                }
                            @endphp
                            <tr>
                                <td class="text-center">
                                <span class="badge bg-primary" style="font-size: 1rem; padding: 8px 12px;">
                                    {{ $numActividades }}
                                </span>
                                    <br><small class="text-muted">actividad(es)</small>
                                </td>
                                <td class="text-center">{{ $orden->fecha->format('d/m/Y') }}</td>
                                <td>{{ $orden->tecnico_nombre }}</td>
                                <td>{{ $orden->cliente_nombre }}</td>
                                <td>
                                    <strong>{{ $orden->equipo_tipo }}</strong>
                                    @if($orden->equipo_marca)
                                        <br><small class="text-muted">{{ $orden->equipo_marca }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($totalPreventivo > 0)
                                        <span class="text-success fw-bold">${{ number_format($totalPreventivo, 2) }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($totalCorrectivo > 0)
                                        <span class="text-danger fw-bold">${{ number_format($totalCorrectivo, 2) }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <strong class="text-primary">${{ number_format($orden->total, 2) }}</strong>
                                </td>
                                <td class="text-center">
                                    @php
                                        $estadoClass = '';
                                        $estadoIcono = '';
                                        switch($orden->estado) {
                                            case 'Pendiente':
                                                $estadoClass = 'bg-warning';
                                                $estadoIcono = 'fa-clock';
                                                break;
                                            case 'En Proceso':
                                                $estadoClass = 'bg-info';
                                                $estadoIcono = 'fa-spinner';
                                                break;
                                            case 'Completado':
                                                $estadoClass = 'bg-success';
                                                $estadoIcono = 'fa-check-circle';
                                                break;
                                            case 'Entregado':
                                                $estadoClass = 'bg-primary';
                                                $estadoIcono = 'fa-truck';
                                                break;
                                            default:
                                                $estadoClass = 'bg-secondary';
                                                $estadoIcono = 'fa-question';
                                        }
                                    @endphp
                                    <span class="badge {{ $estadoClass }}" style="font-size: 0.85rem; padding: 6px 12px;">
                                    <i class="fas {{ $estadoIcono }} me-1"></i>{{ $orden->estado }}
                                </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('ordenes.show', $orden->idorden) }}" class="btn btn-info btn-sm" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('ordenes.edit', $orden->idorden) }}" class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('ordenes.destroy', $orden->idorden) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta orden?')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-5">
                                    <i class="fas fa-clipboard-list fa-3x mb-3 d-block"></i>
                                    No hay órdenes de servicio registradas
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $ordenes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th, .table td {
            vertical-align: middle;
            padding: 12px 8px;
        }
        .badge {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
        .text-success {
            color: #27ae60 !important;
            font-weight: 600;
        }
        .text-danger {
            color: #e74c3c !important;
            font-weight: 600;
        }
        .text-primary {
            color: #2c3e50 !important;
            font-weight: 700;
        }
        .text-muted {
            color: #95a5a6 !important;
        }
        .btn-group .btn {
            margin: 0 2px;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        .bg-dark {
            background-color: #2c3e50 !important;
        }
    </style>
@endpush
