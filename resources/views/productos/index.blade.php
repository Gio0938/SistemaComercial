@extends('layouts.app')

@section('title', 'Gestión de Productos')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-box me-2"></i>Gestión de Productos</h1>

        @if(Auth::user()->esAdmin())
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('productos.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Nuevo Producto
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
                        <th>Marca</th>
                        <th>Disponible</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($productos as $producto)
                        <tr>
                            <td>{{ $producto->idprod }}</td>

                            <td>
                                @if($producto->foto)
                                    <img src="{{ Storage::url($producto->foto) }}" alt="Foto" width="50" height="50" style="object-fit: cover;" class="rounded">
                                @else
                                    <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $producto->nombre }}</strong>
                                @if($producto->descripcion)
                                    <br><small class="text-muted">{{ Str::limit($producto->descripcion, 50) }}</small>
                                @endif
                            </td>

                            <td>${{ number_format($producto->precio, 2) }}</td>

                            <td>
                                @if($producto->marca)
                                    <span class="badge bg-primary">Marca Propia</span>
                                @else
                                    <span class="badge bg-secondary">Otra Marca</span>
                                @endif
                            </td>

                            <td>
                                @if($producto->disponible)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Disponible</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>No Disponible</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-{{ $producto->stock > 0 ? 'success' : 'danger' }}">
                                    {{ $producto->stock }} unidades
                                </span>
                            </td>

                            <td>{{ $producto->categoria ?? 'N/A' }}</td>

                            <td class="table-actions">
                                {{-- 🔵 Botón Ver SIEMPRE visible --}}
                                <a href="{{ route('productos.show', $producto->idprod) }}" class="btn btn-info btn-sm" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- 🟡 Botón Editar solo para admin --}}
                                @if(Auth::user()->esAdmin())
                                    <a href="{{ route('productos.edit', $producto->idprod) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- 🔴 Botón Eliminar solo para admin --}}
                                    <form action="{{ route('productos.destroy', $producto->idprod) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-3"></i>
                                <br>
                                No hay productos registrados
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
