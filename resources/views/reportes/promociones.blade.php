@extends('layouts.app')

@section('title', 'Reporte de Promociones')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tags me-2"></i>Reporte de Promociones
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('reportes.exportar-promociones-pdf') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                            </a>
                            <a href="{{ route('reportes.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Mostrar total de promociones -->
                        @if(isset($promociones))
                            <div class="alert alert-info">
                                <strong>Total de promociones encontradas:</strong> {{ $promociones->count() }}
                            </div>
                        @endif

                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $estadisticas['activas'] ?? 0 }}</h3>
                                        <p>Promociones Activas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $estadisticas['proximas'] ?? 0 }}</h3>
                                        <p>Próximas a Vencer</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>{{ $estadisticas['expiradas'] ?? 0 }}</h3>
                                        <p>Promociones Expiradas</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Promociones -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Descuento</th>
                                    <th>Precio Promocional</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Días Restantes</th>
                                    <th>Estado</th>
                                    <th>Aplicación</th>
                                </thead>
                                </thead>
                                <tbody>
                                @forelse($promociones as $promocion)
                                    <tr>
                                        <td>{{ $promocion->idpromo }}</td>
                                        <td>
                                            <strong>{{ $promocion->nombre }}</strong>
                                            @if($promocion->codigo_promocion)
                                                <br><small class="text-muted">Código: {{ $promocion->codigo_promocion }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $promocion->tipo_promocion }}</span>
                                        </td>
                                        <td>
                                            @if($promocion->tipo_promocion == 'Porcentaje')
                                                <span class="badge bg-primary">{{ $promocion->descuento }}%</span>
                                            @elseif($promocion->tipo_promocion == 'Fijo')
                                                <span class="badge bg-primary">${{ number_format($promocion->descuento, 2) }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $promocion->tipo_promocion }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($promocion->precio_promocional)
                                                ${{ number_format($promocion->precio_promocional, 2) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($promocion->fecha_inicio)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y') }}</td>
                                        <td>
                                            @php
                                                $diasRestantes = now()->diffInDays($promocion->fecha_fin, false);
                                            @endphp
                                            @if($diasRestantes > 0 && $promocion->activa)
                                                <span class="badge bg-warning">{{ ceil($diasRestantes) }} días</span>
                                            @elseif($diasRestantes <= 0 && $promocion->activa)
                                                <span class="badge bg-danger">Expirada</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $hoy = now();
                                                $estado = '';
                                                $color = '';

                                                if (!$promocion->activa) {
                                                    $estado = 'Inactiva';
                                                    $color = 'secondary';
                                                } elseif ($hoy->lt($promocion->fecha_inicio)) {
                                                    $estado = 'Próxima';
                                                    $color = 'warning';
                                                } elseif ($hoy->gt($promocion->fecha_fin)) {
                                                    $estado = 'Expirada';
                                                    $color = 'danger';
                                                } else {
                                                    $estado = 'Activa';
                                                    $color = 'success';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ $estado }}</span>
                                        </td>
                                        <td>
                                            @if($promocion->aplica_todos_servicios)
                                                <span class="badge bg-primary">Todos los Servicios</span>
                                            @elseif($promocion->aplica_todos_productos)
                                                <span class="badge bg-success">Todos los Productos</span>
                                            @elseif($promocion->servicio)
                                                <span class="badge bg-info">Servicio: {{ $promocion->servicio->nombre }}</span>
                                            @elseif($promocion->producto)
                                                <span class="badge bg-info">Producto: {{ $promocion->producto->nombre }}</span>
                                            @else
                                                <span class="badge bg-secondary">Sin definir</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No hay promociones registradas</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Distribución por tipo -->
                        @if(isset($estadisticas['por_tipo']) && $estadisticas['por_tipo']->count() > 0)
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Distribución por Tipo de Promoción</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm">
                                                <thead>
                                                <tr>
                                                    <th>Tipo</th>
                                                    <th>Cantidad</th>
                                                    <th>Porcentaje</th>
                                                </thead>
                                                </thead>
                                                <tbody>
                                                @foreach($estadisticas['por_tipo'] as $tipo)
                                                    <tr>
                                                        <td>{{ $tipo->tipo_promocion }}</td>
                                                        <td>{{ $tipo->total }}</td>
                                                        <td>
                                                            @php
                                                                $total = $promociones->count();
                                                                $porcentaje = $total > 0 ? ($tipo->total / $total) * 100 : 0;
                                                            @endphp
                                                            {{ number_format($porcentaje, 1) }}%
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Resumen por Estado</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <span class="badge bg-success">Activas: {{ $estadisticas['activas'] ?? 0 }}</span>
                                                <span class="badge bg-warning">Próximas: {{ $estadisticas['proximas'] ?? 0 }}</span>
                                                <span class="badge bg-danger">Expiradas: {{ $estadisticas['expiradas'] ?? 0 }}</span>
                                            </div>
                                            <div class="progress mb-2" style="height: 25px;">
                                                @php
                                                    $totalPromos = ($estadisticas['activas'] ?? 0) + ($estadisticas['proximas'] ?? 0) + ($estadisticas['expiradas'] ?? 0);
                                                    $activasPct = $totalPromos > 0 ? (($estadisticas['activas'] ?? 0) / $totalPromos) * 100 : 0;
                                                    $proximasPct = $totalPromos > 0 ? (($estadisticas['proximas'] ?? 0) / $totalPromos) * 100 : 0;
                                                    $expiradasPct = $totalPromos > 0 ? (($estadisticas['expiradas'] ?? 0) / $totalPromos) * 100 : 0;
                                                @endphp
                                                <div class="progress-bar bg-success" style="width: {{ $activasPct }}%">
                                                    {{ number_format($activasPct, 1) }}%
                                                </div>
                                                <div class="progress-bar bg-warning" style="width: {{ $proximasPct }}%">
                                                    {{ number_format($proximasPct, 1) }}%
                                                </div>
                                                <div class="progress-bar bg-danger" style="width: {{ $expiradasPct }}%">
                                                    {{ number_format($expiradasPct, 1) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
