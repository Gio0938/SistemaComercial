@extends('layouts.app')

@section('title', 'Gestión de Servicios')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-concierge-bell me-2"></i>Gestión de Servicios</h1>

        @if(Auth::user()->esAdmin())
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('servicios.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Nuevo Servicio
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
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Tipo</th>
                        <th>Disponible</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($servicios as $servicio)
                        <tr>
                            <td>{{ $servicio->idserv }}</td>

                            <td>
                                @if($servicio->foto)
                                    <img src="{{ Storage::url($servicio->foto) }}" alt="Foto" width="50" height="50"
                                         style="object-fit: cover;" class="rounded">
                                @else
                                    <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $servicio->nombre }}</strong>
                                @if($servicio->descripcion)
                                    <br><small class="text-muted">{{ Str::limit($servicio->descripcion, 50) }}</small>
                                @endif
                            </td>

                            <td>${{ number_format($servicio->precio, 2) }}</td>

                            <td>
                                <span class="badge bg-info">{{ $servicio->tipo_servicio }}</span>
                            </td>

                            <td>
                                @if($servicio->disponible)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Disponible</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>No Disponible</span>
                                @endif
                            </td>

                            <td>{{ $servicio->categoria->nombre ?? 'N/A' }}</td>

                            <td class="table-actions">

                                {{-- 🔵 Todos pueden ver --}}
                                <a href="{{ route('servicios.show', $servicio->idserv) }}" class="btn btn-info btn-sm" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- 🟡 Solo admin puede editar y eliminar --}}
                                @if(Auth::user()->esAdmin())
                                    <a href="{{ route('servicios.edit', $servicio->idserv) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('servicios.destroy', $servicio->idserv) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Estás seguro de eliminar este servicio?')"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-3"></i><br>
                                No hay servicios registrados
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
