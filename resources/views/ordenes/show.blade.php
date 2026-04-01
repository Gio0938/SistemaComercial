@extends('layouts.app')

@section('title', 'Orden #' . $orden->folio)

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-list me-2"></i>Orden de Servicio #{{ $orden->folio }}
                </h3>
                <div class="card-tools">
                    <a href="{{ route('ordenes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                    <a href="{{ route('ordenes.edit', $orden->idorden) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Editar
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Datos Generales -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text">No. Servicio</span>
                                <span class="info-box-number">{{ $orden->folio }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text">Fecha</span>
                                <span class="info-box-number">{{ $orden->fecha->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text">Técnico</span>
                                <span class="info-box-number">{{ $orden->tecnico_nombre }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text">Estado</span>
                                <span class="info-box-number">
                                <span class="{{ $orden->badge_estado }}">{{ $orden->estado }}</span>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cliente -->
                <div class="card mt-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Datos del Cliente</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nombre:</strong> {{ $orden->cliente_nombre }}</p>
                        <p><strong>RFC:</strong> {{ $orden->cliente_rfc ?? 'N/A' }}</p>
                        <p><strong>Correo:</strong> {{ $orden->cliente_email ?? 'N/A' }}</p>
                        <p><strong>Teléfono:</strong> {{ $orden->cliente_telefono ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Equipo -->
                <div class="card mt-3">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="fas fa-laptop me-2"></i>Equipo</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"><strong>Tipo:</strong> {{ $orden->equipo_tipo }}</div>
                            <div class="col-md-3"><strong>Marca:</strong> {{ $orden->equipo_marca ?? 'N/A' }}</div>
                            <div class="col-md-3"><strong>Modelo:</strong> {{ $orden->equipo_modelo ?? 'N/A' }}</div>
                            <div class="col-md-3"><strong>No. Serie:</strong> {{ $orden->equipo_serie ?? 'N/A' }}</div>
                        </div>
                        <div class="mt-2">
                            <strong>Especificaciones:</strong>
                            <p class="text-muted">{{ $orden->especificaciones ?? 'N/A' }}</p>
                        </div>
                        <div class="mt-2">
                            <strong>Diagnóstico:</strong>
                            <p class="text-muted">{{ $orden->diagnostico ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Servicios -->
                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Servicios Realizados</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Servicio</th>
                                    <th>Costo HR</th>
                                    <th>Horas</th>
                                    <th>Refacción</th>
                                    <th>Costo Refacción</th>
                                    <th>Subtotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orden->detalles as $detalle)
                                    <tr>
                                        <td>
                                        <span class="badge bg-{{ $detalle->tipo == 'preventivo' ? 'success' : 'danger' }}">
                                            {{ ucfirst($detalle->tipo) }}
                                        </span>
                                        </td>
                                        <td>{{ $detalle->servicio_nombre }}</td>
                                        <td>${{ number_format($detalle->costo_hr, 2) }}</td>
                                        <td>{{ $detalle->horas }}</td>
                                        <td>{{ $detalle->refaccion_nombre ?? '---' }}</td>
                                        <td>${{ number_format($detalle->costo_refaccion, 2) }}</td>
                                        <td>${{ number_format($detalle->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr class="table-active">
                                    <td colspan="6" class="text-end"><strong>TOTAL:</strong></td>
                                    <td><strong>${{ number_format($orden->total, 2) }}</strong></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
