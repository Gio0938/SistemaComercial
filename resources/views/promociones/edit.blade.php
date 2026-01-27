@extends('layouts.app')

@section('title', 'Editar Promoción')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-edit me-2"></i>Editar Promoción</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('promociones.update', $promocione->idpromo) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <!-- Información Básica -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la Promoción *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $promocione->nombre) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $promocione->descripcion) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_promocion" class="form-label">Tipo de Promoción *</label>
                            <select class="form-control" id="tipo_promocion" name="tipo_promocion" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="Porcentaje" {{ old('tipo_promocion', $promocione->tipo_promocion) == 'Porcentaje' ? 'selected' : '' }}>Porcentaje de descuento</option>
                                <option value="Fijo" {{ old('tipo_promocion', $promocione->tipo_promocion) == 'Fijo' ? 'selected' : '' }}>Monto fijo de descuento</option>
                                <option value="2x1" {{ old('tipo_promocion', $promocione->tipo_promocion) == '2x1' ? 'selected' : '' }}>2x1</option>
                                <option value="3x2" {{ old('tipo_promocion', $promocione->tipo_promocion) == '3x2' ? 'selected' : '' }}>3x2</option>
                                <option value="Envio Gratis" {{ old('tipo_promocion', $promocione->tipo_promocion) == 'Envio Gratis' ? 'selected' : '' }}>Envío Gratis</option>
                            </select>
                        </div>

                        <!-- Campos dinámicos según tipo de promoción -->
                        <div id="campo-descuento" class="mb-3" style="display: none;">
                            <label for="descuento" class="form-label">Valor del Descuento</label>
                            <div class="input-group">
                                <span id="simbolo-descuento" class="input-group-text"></span>
                                <input type="number" step="0.01" class="form-control" id="descuento" name="descuento" value="{{ old('descuento', $promocione->descuento) }}">
                            </div>
                        </div>

                        <div id="campo-precio-promocional" class="mb-3" style="display: none;">
                            <label for="precio_promocional" class="form-label">Precio Promocional</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" id="precio_promocional" name="precio_promocional" value="{{ old('precio_promocional', $promocione->precio_promocional) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="codigo_promocion" class="form-label">Código de Promoción</label>
                            <input type="text" class="form-control" id="codigo_promocion" name="codigo_promocion" value="{{ old('codigo_promocion', $promocione->codigo_promocion) }}" placeholder="Opcional">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Fechas y Límites -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio *</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $promocione->fecha_inicio->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha de Fin *</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', $promocione->fecha_fin->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="limite_usos" class="form-label">Límite de Usos</label>
                            <input type="number" class="form-control" id="limite_usos" name="limite_usos" value="{{ old('limite_usos', $promocione->limite_usos) }}" placeholder="Dejar vacío para ilimitado">
                            <div class="form-text">Número máximo de veces que se puede usar esta promoción</div>
                        </div>

                        <div class="mb-3">
                            <label for="condiciones" class="form-label">Condiciones Especiales</label>
                            <textarea class="form-control" id="condiciones" name="condiciones" rows="3" placeholder="Términos y condiciones adicionales">{{ old('condiciones', $promocione->condiciones) }}</textarea>
                        </div>

                        <!-- Aplicación de la Promoción -->
                        <div class="mb-3">
                            <label class="form-label">Aplicar a:</label>

                            <div class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="aplica_todos_servicios" name="aplica_todos_servicios" {{ old('aplica_todos_servicios', $promocione->aplica_todos_servicios) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aplica_todos_servicios">Todos los Servicios</label>
                                </div>
                            </div>

                            <div class="mb-2" id="select-servicio">
                                <label for="servicio_id" class="form-label">Servicio Específico</label>
                                <select class="form-control" id="servicio_id" name="servicio_id">
                                    <option value="">Seleccionar servicio</option>
                                    @foreach($servicios as $servicio)
                                        <option value="{{ $servicio->idserv }}" {{ old('servicio_id', $promocione->servicio_id) == $servicio->idserv ? 'selected' : '' }}>
                                            {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="aplica_todos_productos" name="aplica_todos_productos" {{ old('aplica_todos_productos', $promocione->aplica_todos_productos) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aplica_todos_productos">Todos los Productos</label>
                                </div>
                            </div>

                            <div class="mb-2" id="select-producto">
                                <label for="producto_id" class="form-label">Producto Específico</label>
                                <select class="form-control" id="producto_id" name="producto_id">
                                    <option value="">Seleccionar producto</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->idprod }}" {{ old('producto_id', $promocione->producto_id) == $producto->idprod ? 'selected' : '' }}>
                                            {{ $producto->nombre }} - ${{ number_format($producto->precio, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Checkboxes de Configuración -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="activa" name="activa" {{ old('activa', $promocione->activa) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activa">Promoción Activa</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Actualizar Promoción
                    </button>
                    <a href="{{ route('promociones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tipoPromocion = document.getElementById('tipo_promocion');
                const campoDescuento = document.getElementById('campo-descuento');
                const campoprecioPromocional = document.getElementById('campo-precio-promocional');
                const simboloDescuento = document.getElementById('simbolo-descuento');
                const aplicaTodosServicios = document.getElementById('aplica_todos_servicios');
                const aplicaTodosProductos = document.getElementById('aplica_todos_productos');
                const selectServicio = document.getElementById('select-servicio');
                const selectProducto = document.getElementById('select-producto');

                // Manejar cambios en el tipo de promoción
                function actualizarCamposPromocion() {
                    const tipo = tipoPromocion.value;

                    campoDescuento.style.display = 'none';
                    campoprecioPromocional.style.display = 'none';

                    if (tipo === 'Porcentaje') {
                        campoDescuento.style.display = 'block';
                        simboloDescuento.textContent = '%';
                    } else if (tipo === 'Fijo') {
                        campoDescuento.style.display = 'block';
                        simboloDescuento.textContent = '$';
                    } else if (tipo === '2x1' || tipo === '3x2') {
                        // No mostrar campos adicionales
                    } else if (tipo === 'Envio Gratis') {
                        // No mostrar campos adicionales
                    }
                }

                // Manejar checkboxes de aplicación
                function actualizarSelects() {
                    if (aplicaTodosServicios.checked) {
                        selectServicio.style.display = 'none';
                        document.getElementById('servicio_id').value = '';
                    } else {
                        selectServicio.style.display = 'block';
                    }

                    if (aplicaTodosProductos.checked) {
                        selectProducto.style.display = 'none';
                        document.getElementById('producto_id').value = '';
                    } else {
                        selectProducto.style.display = 'block';
                    }
                }

                tipoPromocion.addEventListener('change', actualizarCamposPromocion);
                aplicaTodosServicios.addEventListener('change', actualizarSelects);
                aplicaTodosProductos.addEventListener('change', actualizarSelects);

                // Inicializar
                actualizarCamposPromocion();
                actualizarSelects();
            });
        </script>
    @endpush
@endsection
