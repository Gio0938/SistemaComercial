@extends('layouts.app')

@section('title', 'Gestión de Promociones')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-tags me-2"></i>Gestión de Promociones</h1>

        {{-- Solo admin puede crear --}}
        @if(Auth::user()->esAdmin())
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('promociones.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Nueva Promoción
                </a>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Descuento</th>
                        <th>Fechas</th>
                        <th>Estado</th>
                        <th>Aplicación</th>
                        <th>Usos</th>
                        <th>Acciones</th>
                    </tr>
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
                                    {{ $promocion->descuento }}%
                                @elseif($promocion->tipo_promocion == 'Fijo')
                                    ${{ number_format($promocion->descuento, 2) }}
                                @elseif($promocion->precio_promocional)
                                    ${{ number_format($promocion->precio_promocional, 2) }}
                                @else
                                    {{ $promocion->tipo_promocion }}
                                @endif
                            </td>

                            <td>
                                <small>
                                    <strong>Inicio:</strong> {{ $promocion->fecha_inicio->format('d/m/Y') }}<br>
                                    <strong>Fin:</strong> {{ $promocion->fecha_fin->format('d/m/Y') }}
                                </small>
                            </td>

                            <td>
                                @php
                                    $estado = $promocion->estado;
                                    $badgeClass = [
                                        'activa' => 'bg-success',
                                        'programada' => 'bg-warning',
                                        'expirada' => 'bg-secondary',
                                        'inactiva' => 'bg-danger'
                                    ][$estado];
                                @endphp

                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($estado) }}
                                </span>

                                @if($promocion->dias_restantes > 0 && $estado === 'activa')
                                    <br><small class="text-muted">{{ $promocion->dias_restantes }} días restantes</small>
                                @endif
                            </td>

                            <td>
                                @if($promocion->aplica_todos_servicios)
                                    <span class="badge bg-primary">Todos Servicios</span>
                                @elseif($promocion->servicio)
                                    <span class="badge bg-info">Servicio: {{ $promocion->servicio->nombre }}</span>
                                @elseif($promocion->aplica_todos_productos)
                                    <span class="badge bg-primary">Todos Productos</span>
                                @elseif($promocion->producto)
                                    <span class="badge bg-info">Producto: {{ $promocion->producto->nombre }}</span>
                                @else
                                    <span class="badge bg-secondary">No especificado</span>
                                @endif
                            </td>

                            <td>
                                @if($promocion->limite_usos)
                                    <small>{{ $promocion->usos_actuales }}/{{ $promocion->limite_usos }}</small>
                                @else
                                    <small>Ilimitado</small>
                                @endif
                            </td>

                            <td class="table-actions">
                                {{-- 🔵 Ver: siempre disponible --}}
                                <a href="{{ route('promociones.show', $promocion->idpromo) }}" class="btn btn-info btn-sm" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- 🟡 Editar, Activar/Desactivar, Eliminar → solo admin --}}
                                @if(Auth::user()->esAdmin())
                                    <a href="{{ route('promociones.edit', $promocion->idpromo) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('promociones.toggle', $promocion->idpromo) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="btn btn-{{ $promocion->activa ? 'secondary' : 'success' }} btn-sm"
                                                title="{{ $promocion->activa ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas fa-{{ $promocion->activa ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('promociones.destroy', $promocion->idpromo) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Estás seguro de eliminar esta promoción?')"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-tags fa-2x mb-3"></i>
                                <br>No hay promociones registradas
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
