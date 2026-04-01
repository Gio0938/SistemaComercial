@extends('layouts.app')

@section('title', 'Reporte de Servicios')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-concierge-bell me-2"></i>Reporte de Servicios
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('reportes.exportar-servicios-pdf') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                            </a>
                            <a href="{{ route('reportes.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Mostrar datos para depuración -->
                        @if(isset($servicios))
                            <div class="alert alert-info">
                                <strong>Total de servicios encontrados:</strong> {{ $servicios->count() }}
                            </div>
                        @endif

                        <!-- Estadísticas -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $estadisticas['total_activos'] ?? 0 }}</h3>
                                        <p>Servicios Activos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>${{ number_format($estadisticas['precio_promedio'] ?? 0, 2) }}</h3>
                                        <p>Precio Promedio</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $estadisticas['total_destacados'] ?? 0 }}</h3>
                                        <p>Servicios Destacados</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Servicios -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Duración</th>
                                    <th>Disponible</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($servicios as $servicio)
                                    <tr>
                                        <td>{{ $servicio->idserv }}</td>
                                        <td>{{ $servicio->nombre }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $servicio->tipo_servicio }}</span>
                                        </td>
                                        <td>{{ $servicio->categoria ?? 'N/A' }}</td>
                                        <td>${{ number_format($servicio->precio, 2) }}</td>
                                        <td>{{ $servicio->duracion ? $servicio->duracion . ' hrs' : 'N/A' }}</td>
                                        <td>
                                            @if($servicio->disponible)
                                                <span class="badge bg-success">Sí</span>
                                            @else
                                                <span class="badge bg-danger">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No hay servicios registrados</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
