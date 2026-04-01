@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar me-2"></i>Panel de Reportes
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $stats['total_productos'] }}</h3>
                                        <p>Productos</p>
                                    </div>
                                    <div class="icon"><i class="fas fa-box"></i></div>
                                    <a href="{{ route('reportes.productos') }}" class="small-box-footer">Ver reporte <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $stats['total_servicios'] }}</h3>
                                        <p>Servicios</p>
                                    </div>
                                    <div class="icon"><i class="fas fa-concierge-bell"></i></div>
                                    <a href="{{ route('reportes.servicios') }}" class="small-box-footer">Ver reporte <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $stats['total_promociones'] }}</h3>
                                        <p>Promociones</p>
                                    </div>
                                    <div class="icon"><i class="fas fa-tags"></i></div>
                                    <a href="{{ route('reportes.promociones') }}" class="small-box-footer">Ver reporte <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>${{ number_format($stats['ingresos_totales'], 2) }}</h3>
                                        <p>Ingresos Totales</p>
                                    </div>
                                    <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                                    <a href="{{ route('reportes.ventas') }}" class="small-box-footer">Ver reporte <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
