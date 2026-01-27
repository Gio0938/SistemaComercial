@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-edit me-2"></i>Editar Producto</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('productos.update', $producto->idprod) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="{{ old('precio', $producto->precio) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto del Producto</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            @if($producto->foto)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($producto->foto) }}" alt="Foto actual" width="100" class="rounded">
                                    <small class="text-muted d-block">Foto actual</small>
                                </div>
                            @endif
                            <div class="form-text">Dejar vacío para mantener la foto actual</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock *</label>
                                    <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $producto->stock) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_piezas" class="form-label">Número de Piezas</label>
                                    <input type="number" class="form-control" id="numero_piezas" name="numero_piezas" value="{{ old('numero_piezas', $producto->numero_piezas) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SELECT de Categoría -->
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria" name="categoria">
                            <option value="">-- Selecciona una categoría --</option>

                            @php
                                $categorias = [
                                    'Laptops',
                                    'Computadoras de Escritorio',
                                    'Monitores',
                                    'Teclados',
                                    'Mouse',
                                    'Audífonos',
                                    'Impresoras',
                                    'Componentes',
                                    'Redes',
                                    'Accesorios'
                                ];
                            @endphp

                            @foreach ($categorias as $cat)
                                <option value="{{ $cat }}"
                                    {{ old('categoria', $producto->categoria) == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <!-- SELECT de Proveedor -->
                    <div class="mb-3">
                        <label for="proveedor" class="form-label">Proveedor</label>
                        <select class="form-select" id="proveedor" name="proveedor">
                            <option value="">-- Selecciona un proveedor --</option>

                            @php
                                $proveedores = [
                                    'Cyberport',
                                    'Intcomex',
                                    'Ingram Micro',
                                    'CDW',
                                    'Tech Depot',
                                    'HP',
                                    'Dell',
                                    'Lenovo',
                                    'Acer',
                                    'Asus'
                                ];
                            @endphp

                            @foreach ($proveedores as $prov)
                                <option value="{{ $prov }}"
                                    {{ old('proveedor', $producto->proveedor) == $prov ? 'selected' : '' }}>
                                    {{ $prov }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="peso" class="form-label">Peso (kg)</label>
                                    <input type="number" step="0.01" class="form-control" id="peso" name="peso" value="{{ old('peso', $producto->peso) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dimensiones" class="form-label">Dimensiones</label>
                                    <input type="text" class="form-control" id="dimensiones" name="dimensiones" value="{{ old('dimensiones', $producto->dimensiones) }}" placeholder="Ej: 10x20x30 cm">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="codigo_barras" class="form-label">Código de Barras</label>
                            <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" value="{{ old('codigo_barras', $producto->codigo_barras) }}">
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="marca" name="marca" {{ old('marca', $producto->marca) ? 'checked' : '' }}>
                                <label class="form-check-label" for="marca">Marca Propia</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="disponible" name="disponible" {{ old('disponible', $producto->disponible) ? 'checked' : '' }}>
                                <label class="form-check-label" for="disponible">Disponible</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Actualizar Producto
                    </button>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
