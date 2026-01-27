@extends('layouts.app')

@section('title', 'Crear Nuevo Producto')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-plus me-2"></i>Crear Nuevo Producto</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="{{ old('precio') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto del Producto</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <div class="form-text">Formatos: JPEG, PNG, JPG, GIF. Máx: 2MB</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock *</label>
                                    <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_piezas" class="form-label">Número de Piezas</label>
                                    <input type="number" class="form-control" id="numero_piezas" name="numero_piezas" value="{{ old('numero_piezas') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <!-- SELECT de Categoría -->
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <select class="form-select" id="categoria" name="categoria">
                                <option value="">-- Selecciona una categoría --</option>

                                <!-- Categorías comunes -->
                                <option value="Laptops" {{ old('categoria') == 'Laptops' ? 'selected' : '' }}>Laptops</option>
                                <option value="Computadoras de Escritorio" {{ old('categoria') == 'Computadoras de Escritorio' ? 'selected' : '' }}>Computadoras de Escritorio</option>
                                <option value="Monitores" {{ old('categoria') == 'Monitores' ? 'selected' : '' }}>Monitores</option>
                                <option value="Teclados" {{ old('categoria') == 'Teclados' ? 'selected' : '' }}>Teclados</option>
                                <option value="Mouse" {{ old('categoria') == 'Mouse' ? 'selected' : '' }}>Mouse</option>
                                <option value="Audífonos" {{ old('categoria') == 'Audífonos' ? 'selected' : '' }}>Audífonos</option>
                                <option value="Impresoras" {{ old('categoria') == 'Impresoras' ? 'selected' : '' }}>Impresoras</option>
                                <option value="Componentes" {{ old('categoria') == 'Componentes' ? 'selected' : '' }}>Componentes</option>
                                <option value="Redes" {{ old('categoria') == 'Redes' ? 'selected' : '' }}>Redes</option>
                                <option value="Accesorios" {{ old('categoria') == 'Accesorios' ? 'selected' : '' }}>Accesorios</option>
                            </select>
                        </div>

                        <!-- SELECT de Proveedor -->
                        <div class="mb-3">
                            <label for="proveedor" class="form-label">Proveedor</label>
                            <select class="form-select" id="proveedor" name="proveedor">
                                <option value="">-- Selecciona un proveedor --</option>

                                <!-- Proveedores comunes -->
                                <option value="Cyberport" {{ old('proveedor') == 'Cyberport' ? 'selected' : '' }}>Cyberport</option>
                                <option value="Intcomex" {{ old('proveedor') == 'Intcomex' ? 'selected' : '' }}>Intcomex</option>
                                <option value="Ingram Micro" {{ old('proveedor') == 'Ingram Micro' ? 'selected' : '' }}>Ingram Micro</option>
                                <option value="CDW" {{ old('proveedor') == 'CDW' ? 'selected' : '' }}>CDW</option>
                                <option value="Tech Depot" {{ old('proveedor') == 'Tech Depot' ? 'selected' : '' }}>Tech Depot</option>
                                <option value="HP" {{ old('proveedor') == 'HP' ? 'selected' : '' }}>HP</option>
                                <option value="Dell" {{ old('proveedor') == 'Dell' ? 'selected' : '' }}>Dell</option>
                                <option value="Lenovo" {{ old('proveedor') == 'Lenovo' ? 'selected' : '' }}>Lenovo</option>
                                <option value="Acer" {{ old('proveedor') == 'Acer' ? 'selected' : '' }}>Acer</option>
                                <option value="Asus" {{ old('proveedor') == 'Asus' ? 'selected' : '' }}>Asus</option>
                            </select>
                        </div>

                    </div>


                    <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="peso" class="form-label">Peso (kg)</label>
                                    <input type="number" step="0.01" class="form-control" id="peso" name="peso" value="{{ old('peso') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dimensiones" class="form-label">Dimensiones</label>
                                    <input type="text" class="form-control" id="dimensiones" name="dimensiones" value="{{ old('dimensiones') }}" placeholder="Ej: 10x20x30 cm">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="codigo_barras" class="form-label">Código de Barras</label>
                            <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" value="{{ old('codigo_barras') }}">
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="marca" name="marca" {{ old('marca') ? 'checked' : '' }}>
                                <label class="form-check-label" for="marca">Marca Propia</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="disponible" name="disponible" {{ old('disponible') ? 'checked' : '' }}>
                                <label class="form-check-label" for="disponible">Disponible</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Guardar Producto
                    </button>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
