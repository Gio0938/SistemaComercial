@extends('layouts.app')

@section('title', 'Reporte de Ventas')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line me-2"></i>Reporte de Ventas
                </h3>
                <div class="card-tools">
                    <a href="{{ route('reportes.exportar-ventas-pdf') }}" class="btn btn-danger btn-sm">
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
                                <h3>{{ $estadisticas['total_ventas'] }}</h3>
                                <p>Total Ventas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>${{ number_format($estadisticas['total_ingresos'], 2) }}</h3>
                                <p>Ingresos Totales</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>${{ number_format($estadisticas['promedio_venta'], 2) }}</h3>
                                <p>Promedio por Venta</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $estadisticas['ventas_hoy'] }}</h3>
                                <p>Ventas Hoy</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Ventas -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Empleado</th>
                            <th>Subtotal</th>
                            <th>IVA</th>
                            <th>Total</th>
                        </thead>
                        </thead>
                        <tbody>
                        @forelse($ventas as $venta)
                            <tr>
                                <td>{{ $venta->folio }}</td>
                                <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $venta->cliente->nombre ?? 'Público' }}</td>
                                <td>{{ $venta->usuario->name ?? 'N/A' }}</td>
                                <td>${{ number_format($venta->subtotal, 2) }}</td>
                                <td>${{ number_format($venta->iva, 2) }}</td>
                                <td>${{ number_format($venta->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay ventas registradas</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $ventas->links() }}
            </div>
        </div>
    </div>
@endsection
