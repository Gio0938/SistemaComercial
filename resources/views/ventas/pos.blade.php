@extends('layouts.app')

@section('title', 'Punto de Venta - Computadoras')

@push('styles')
    <style>
        :root {
            --pos-primary: #2c3e50;
            --pos-success: #27ae60;
            --pos-warning: #f39c12;
            --pos-danger: #e74c3c;
            --pos-info: #3498db;
        }

        .pos-card {
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            border: none;
            overflow: hidden;
        }

        .pos-card .card-header {
            background: linear-gradient(135deg, var(--pos-primary) 0%, #34495e 100%);
            color: white;
            padding: 15px 20px;
            border: none;
        }

        .tipo-venta-group {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .tipo-venta-btn {
            flex: 1;
            text-align: center;
        }

        .tipo-venta-btn input[type="radio"] {
            display: none;
        }

        .tipo-venta-btn label {
            display: block;
            padding: 15px;
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .tipo-venta-btn label i {
            font-size: 2rem;
            display: block;
            margin-bottom: 8px;
        }

        .tipo-venta-btn input[type="radio"]:checked + label {
            background: linear-gradient(135deg, var(--pos-success) 0%, #219a52 100%);
            color: white;
            border-color: var(--pos-success);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .panel-venta {
            transition: all 0.3s ease;
        }

        .form-field {
            margin-bottom: 20px;
        }

        .form-field label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
            display: block;
        }

        .form-field .required {
            color: #e74c3c;
        }

        .form-field input:read-only {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .tabla-detalle {
            max-height: 400px;
            overflow-y: auto;
            background: white;
            border-radius: 8px;
        }

        .tabla-detalle table {
            margin-bottom: 0;
            font-size: 12px;
            width: 100%;
        }

        .tabla-detalle thead {
            background: var(--pos-primary);
            color: white;
            position: sticky;
            top: 0;
        }

        .tabla-detalle thead th {
            padding: 10px 6px;
            font-size: 11px;
            text-align: center;
            font-weight: 600;
        }

        .tabla-detalle tbody td {
            padding: 8px 6px;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
        }

        .tabla-detalle tbody tr:hover {
            background: #f8f9fa;
        }

        .total-box {
            background: linear-gradient(135deg, var(--pos-primary) 0%, #34495e 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .total-box .total-amount {
            font-size: 28px;
            font-weight: bold;
        }

        .btn-agregar {
            background: linear-gradient(135deg, var(--pos-success) 0%, #219a52 100%);
            color: white;
            padding: 12px;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            width: 100%;
            font-weight: bold;
        }

        .btn-agregar:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .empleado-info {
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 5px 10px;
        }

        .garantia-group {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .precio-final {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--pos-success);
        }

        .text-na {
            color: #95a5a6;
            font-style: italic;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header con información del empleado -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card pos-card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0">
                                    <i class="fas fa-laptop-code me-2"></i>
                                    Venta de Computadoras
                                </h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="empleado-info">
                                            <i class="fas fa-user-circle me-1"></i>
                                            <strong>{{ Auth::user()->name }}</strong><br>
                                            <small><i class="fas fa-id-card me-1"></i>RFC: {{ Auth::user()->rfc ?? 'No registrado' }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="empleado-info">
                                            <i class="fas fa-ticket-alt me-1"></i>
                                            <strong>No.Ticket: {{ $nuevoFolio }}</strong><br>
                                            <small><i class="fas fa-calendar me-1"></i>{{ date('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Panel Izquierdo - Formulario -->
            <div class="col-md-5">
                <!-- Datos del Cliente -->
                <div class="card pos-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Datos del cliente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label class="form-label small">Nombre</label>
                                <input type="text" class="form-control" id="cliente_nombre" placeholder="Nombre del cliente">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small">RFC</label>
                                <input type="text" class="form-control" id="cliente_rfc" placeholder="RFC (opcional)">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small">Teléfono</label>
                                <input type="tel" class="form-control" id="cliente_telefono" placeholder="Teléfono">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tipo de Venta - Radio Button -->
                <div class="card pos-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-tag me-2"></i>Tipo de venta
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="tipo-venta-group">
                            <div class="tipo-venta-btn">
                                <input type="radio" name="tipo_venta" id="tipo_periferico" value="periferico" checked>
                                <label for="tipo_periferico">
                                    <i class="fas fa-keyboard"></i>
                                    Periférico
                                </label>
                            </div>
                            <div class="tipo-venta-btn">
                                <input type="radio" name="tipo_venta" id="tipo_equipo" value="equipo">
                                <label for="tipo_equipo">
                                    <i class="fas fa-desktop"></i>
                                    Equipo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel PERIFÉRICO -->
                <div id="panel_periferico" class="panel-venta">
                    <div class="card pos-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-box-open me-2"></i>Agregar Periférico
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="form-field">
                                <label>Categoría <span class="required">*</span></label>
                                <select class="form-select" id="categoria_periferico">
                                    <option value="">Seleccione una categoría...</option>
                                    @foreach($categoriasPeriferico as $categoria)
                                        <option value="{{ $categoria }}">{{ $categoria }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-field">
                                <label>Producto <span class="required">*</span></label>
                                <select class="form-select" id="producto_periferico" disabled>
                                    <option value="">Primero seleccione una categoría</option>
                                </select>
                            </div>

                            <div class="form-field">
                                <label>Cantidad <span class="required">*</span></label>
                                <input type="number" class="form-control" id="cantidad_periferico" value="1" min="1">
                            </div>

                            <div class="form-field">
                                <label>Precio unitario</label>
                                <input type="text" class="form-control" id="precio_periferico" readonly placeholder="Seleccione un producto">
                            </div>

                            <div class="form-field">
                                <label>Especificaciones</label>
                                <textarea class="form-control" id="especificaciones_periferico" rows="3" placeholder="Especificaciones del producto..."></textarea>
                            </div>

                            <div class="form-field">
                                <label>Total</label>
                                <input type="text" class="form-control precio-final" id="total_periferico" readonly value="$0.00">
                            </div>

                            <button class="btn-agregar" onclick="agregarPeriferico()">
                                <i class="fas fa-cart-plus me-2"></i> AGREGAR
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Panel EQUIPO -->
                <div id="panel_equipo" class="panel-venta" style="display: none;">
                    <div class="card pos-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-desktop me-2"></i>Agregar Equipo
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="form-field">
                                <label>Tipo de equipo <span class="required">*</span></label>
                                <select class="form-select" id="tipo_equipo_select">
                                    <option value="">Seleccione un tipo...</option>
                                    <option value="Laptop">💻 Laptop</option>
                                    <option value="Computadora de Escritorio">🖥️ Computadora de Escritorio</option>
                                </select>
                            </div>

                            <div class="form-field">
                                <label>Producto <span class="required">*</span></label>
                                <select class="form-select" id="producto_equipo" disabled>
                                    <option value="">Primero seleccione un tipo de equipo</option>
                                </select>
                            </div>

                            <div class="form-field">
                                <label>Cantidad <span class="required">*</span></label>
                                <input type="number" class="form-control" id="cantidad_equipo" value="1" min="1">
                            </div>

                            <div class="form-field">
                                <label>Garantía <span class="required">*</span></label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="garantia_equipo" id="garantia_si" value="1" checked>
                                        <label class="form-check-label" for="garantia_si">Sí</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="garantia_equipo" id="garantia_no" value="0">
                                        <label class="form-check-label" for="garantia_no">No</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-field" id="duracion_group">
                                <label>Duración de garantía</label>
                                <select class="form-select" id="duracion_equipo">
                                    <option value="1">1 año de soporte (+10%)</option>
                                    <option value="2">2 años de soporte (+20%)</option>
                                    <option value="3">3 años de soporte (+30%)</option>
                                </select>
                            </div>

                            <div class="form-field">
                                <label>Especificaciones</label>
                                <textarea class="form-control" id="especificaciones_equipo" rows="3" placeholder="Especificaciones del equipo..."></textarea>
                            </div>

                            <div class="form-field">
                                <label>Precio base</label>
                                <input type="text" class="form-control" id="precio_base_equipo" readonly placeholder="Seleccione un producto">
                            </div>

                            <div class="form-field">
                                <label>Precio final</label>
                                <input type="text" class="form-control precio-final" id="precio_final_equipo" readonly value="$0.00">
                            </div>

                            <div class="form-field">
                                <label>Total (cantidad × precio final)</label>
                                <input type="text" class="form-control precio-final" id="total_equipo" readonly value="$0.00">
                            </div>

                            <button class="btn-agregar" onclick="agregarEquipo()">
                                <i class="fas fa-cart-plus me-2"></i> AGREGAR
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Derecho -->
            <div class="col-md-7">
                <!-- SOLO UNA TABLA: Productos Agregados (Carrito actual) -->
                <div class="card pos-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Productos Agregados
                            <span class="badge bg-light text-dark ms-2" id="contador_items">0</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="tabla-detalle">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Garantía</th>
                                    <th>Total</th>
                                    <th width="30">Opciones</th>
                                </tr>
                                </thead>
                                <tbody id="tabla_detalle">
                                <tr><td colspan="8" class="text-center text-muted py-4">No hay productos agregados</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="total-box">
                                    <div class="total-amount" id="total_venta">$0.00</div>
                                    <small>Total de la venta</small>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <button class="btn btn-success w-100 py-2" onclick="procesarVenta(event)">
                                    <i class="fas fa-check-circle me-1"></i> PROCESAR VENTA
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-danger w-100 py-2" onclick="cancelarVenta()">
                                    <i class="fas fa-trash-alt me-1"></i> CANCELAR
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========== HISTORIAL DE VENTAS (DEBAJO) ========== -->
                <div class="card pos-card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Historial de Ventas - {{ Auth::user()->name }}
                            <span class="badge bg-light text-dark ms-2" id="total_historial">0</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="tabla-detalle" style="max-height: 300px;">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Garantía</th>
                                    <th>Total</th>
                                    <th>Especificaciones</th>
                                    <th>Duración</th>
                                    <th width="60">Opciones</th>
                                </tr>
                                </thead>
                                <tbody id="tabla_historial">
                                <tr><td colspan="8" class="text-center text-muted py-4">Cargando historial...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



    <!-- Campo oculto para el folio actual -->
    <input type="hidden" id="folio_actual" value="{{ $nuevoFolio }}">
    <div class="loading-overlay" id="loading_overlay">
        <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let carrito = [];
        let totalVenta = 0;
        let productosData = @json($productos);

        // Elementos DOM
        const tipoPeriferico = document.getElementById('tipo_periferico');
        const tipoEquipo = document.getElementById('tipo_equipo');
        const panelPeriferico = document.getElementById('panel_periferico');
        const panelEquipo = document.getElementById('panel_equipo');

        // PANEL PERIFÉRICO
        const categoriaSelect = document.getElementById('categoria_periferico');
        const productoPeriferico = document.getElementById('producto_periferico');
        const cantidadPeriferico = document.getElementById('cantidad_periferico');
        const precioPeriferico = document.getElementById('precio_periferico');
        const totalPeriferico = document.getElementById('total_periferico');

        // PANEL EQUIPO
        const tipoEquipoSelect = document.getElementById('tipo_equipo_select');
        const productoEquipo = document.getElementById('producto_equipo');
        const cantidadEquipo = document.getElementById('cantidad_equipo');
        const garantiaSi = document.getElementById('garantia_si');
        const garantiaNo = document.getElementById('garantia_no');
        const duracionGroup = document.getElementById('duracion_group');
        const duracionEquipo = document.getElementById('duracion_equipo');
        const precioBaseEquipo = document.getElementById('precio_base_equipo');
        const precioFinalEquipo = document.getElementById('precio_final_equipo');
        const totalEquipo = document.getElementById('total_equipo');

        // Cambiar paneles
        tipoPeriferico.addEventListener('change', function() {
            if (this.checked) {
                panelPeriferico.style.display = 'block';
                panelEquipo.style.display = 'none';
                resetearFormularioPeriferico();
            }
        });

        tipoEquipo.addEventListener('change', function() {
            if (this.checked) {
                panelPeriferico.style.display = 'none';
                panelEquipo.style.display = 'block';
                resetearFormularioEquipo();
            }
        });

        // ==================== PERIFÉRICO ====================
        categoriaSelect.addEventListener('change', function() {
            const categoria = this.value;

            if (!categoria) {
                productoPeriferico.innerHTML = '<option value="">Seleccione una categoría primero</option>';
                productoPeriferico.disabled = true;
                precioPeriferico.value = '';
                totalPeriferico.value = '$0.00';
                return;
            }

            const productosFiltrados = productosData.filter(p => p.categoria === categoria);

            productoPeriferico.innerHTML = '<option value="">Seleccione un producto...</option>';

            productosFiltrados.forEach(producto => {
                productoPeriferico.innerHTML += `<option value="${producto.idprod}" data-precio="${producto.precio}" data-stock="${producto.stock}" data-categoria="${producto.categoria}">
                ${producto.nombre} - $${producto.precio} (Stock: ${producto.stock})
            </option>`;
            });

            productoPeriferico.disabled = false;
        });

        productoPeriferico.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const precio = selectedOption.getAttribute('data-precio');

            if (precio) {
                precioPeriferico.value = `$${parseFloat(precio).toFixed(2)}`;
                calcularTotalPeriferico();
            } else {
                precioPeriferico.value = '';
                totalPeriferico.value = '$0.00';
            }
        });

        function calcularTotalPeriferico() {
            const cantidad = parseInt(cantidadPeriferico.value) || 0;
            const precioTexto = precioPeriferico.value;
            const precio = parseFloat(precioTexto.replace('$', '')) || 0;
            const total = cantidad * precio;
            totalPeriferico.value = `$${total.toFixed(2)}`;
        }

        cantidadPeriferico.addEventListener('input', calcularTotalPeriferico);

        function agregarPeriferico() {
            const categoria = categoriaSelect.value;
            const productoOption = productoPeriferico.options[productoPeriferico.selectedIndex];
            const productoId = productoPeriferico.value;
            const productoNombre = productoOption.text?.split(' - ')[0];
            const cantidad = parseInt(cantidadPeriferico.value);
            const precioTexto = precioPeriferico.value;
            const precio = parseFloat(precioTexto.replace('$', ''));
            const especificaciones = document.getElementById('especificaciones_periferico').value || '-';

            if (!categoria) {
                alert('❌ Seleccione una categoría');
                return;
            }

            if (!productoId) {
                alert('❌ Seleccione un producto');
                return;
            }

            if (isNaN(cantidad) || cantidad < 1) {
                alert('❌ Cantidad inválida');
                return;
            }

            const subtotal = cantidad * precio;

            carrito.push({
                tipo: 'Periférico',
                nombre: productoNombre,
                categoria: categoria,
                cantidad: cantidad,
                precio: precio,
                subtotal: subtotal,
                garantia: false,
                duracion_garantia: null,
                especificaciones: especificaciones
            });

            calcularTotalGeneral();
            actualizarTabla();
            resetearFormularioPeriferico();
            alert(`✅ ${productoNombre} agregado al carrito`);
        }

        function resetearFormularioPeriferico() {
            categoriaSelect.value = '';
            productoPeriferico.innerHTML = '<option value="">Seleccione una categoría primero</option>';
            productoPeriferico.disabled = true;
            cantidadPeriferico.value = '1';
            precioPeriferico.value = '';
            totalPeriferico.value = '$0.00';
            document.getElementById('especificaciones_periferico').value = '';
        }

        // ==================== EQUIPO ====================
        tipoEquipoSelect.addEventListener('change', function() {
            const tipo = this.value;

            if (!tipo) {
                productoEquipo.innerHTML = '<option value="">Seleccione un tipo de equipo primero</option>';
                productoEquipo.disabled = true;
                precioBaseEquipo.value = '';
                precioFinalEquipo.value = '$0.00';
                totalEquipo.value = '$0.00';
                return;
            }

            const productosFiltrados = productosData.filter(p => p.categoria === tipo);

            productoEquipo.innerHTML = '<option value="">Seleccione un equipo...</option>';

            productosFiltrados.forEach(producto => {
                productoEquipo.innerHTML += `<option value="${producto.idprod}" data-precio="${producto.precio}" data-stock="${producto.stock}">
                ${producto.nombre} - $${producto.precio} (Stock: ${producto.stock})
            </option>`;
            });

            productoEquipo.disabled = false;
        });

        productoEquipo.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const precio = selectedOption.getAttribute('data-precio');

            if (precio) {
                precioBaseEquipo.value = `$${parseFloat(precio).toFixed(2)}`;
                calcularPrecioFinalEquipo();
            } else {
                precioBaseEquipo.value = '';
                precioFinalEquipo.value = '$0.00';
                totalEquipo.value = '$0.00';
            }
        });

        garantiaSi.addEventListener('change', function() {
            duracionGroup.style.display = 'block';
            calcularPrecioFinalEquipo();
        });

        garantiaNo.addEventListener('change', function() {
            duracionGroup.style.display = 'none';
            calcularPrecioFinalEquipo();
        });

        function calcularPrecioFinalEquipo() {
            const precioTexto = precioBaseEquipo.value;
            const precioBase = parseFloat(precioTexto.replace('$', '')) || 0;
            const tieneGarantia = garantiaSi.checked;
            let precioFinal = precioBase;

            if (tieneGarantia) {
                const anios = parseInt(duracionEquipo.value);
                switch(anios) {
                    case 1: precioFinal = precioBase * 1.10; break;
                    case 2: precioFinal = precioBase * 1.20; break;
                    case 3: precioFinal = precioBase * 1.30; break;
                }
            }

            precioFinalEquipo.value = `$${precioFinal.toFixed(2)}`;
            calcularTotalEquipo();
        }

        function calcularTotalEquipo() {
            const cantidad = parseInt(cantidadEquipo.value) || 0;
            const precioTexto = precioFinalEquipo.value;
            const precio = parseFloat(precioTexto.replace('$', '')) || 0;
            const total = cantidad * precio;
            totalEquipo.value = `$${total.toFixed(2)}`;
        }

        duracionEquipo.addEventListener('change', calcularPrecioFinalEquipo);
        cantidadEquipo.addEventListener('input', calcularTotalEquipo);

        function agregarEquipo() {
            const tipoEquipoVal = tipoEquipoSelect.value;
            const productoOption = productoEquipo.options[productoEquipo.selectedIndex];
            const productoId = productoEquipo.value;
            const productoNombre = productoOption.text?.split(' - ')[0];
            const cantidad = parseInt(cantidadEquipo.value);
            const tieneGarantia = garantiaSi.checked;
            let duracionTexto = null;

            if (tieneGarantia) {
                const anios = duracionEquipo.value;
                switch(anios) {
                    case '1': duracionTexto = '1 año de soporte'; break;
                    case '2': duracionTexto = '2 años de soporte'; break;
                    case '3': duracionTexto = '3 años de soporte'; break;
                }
            }

            const precioTexto = precioFinalEquipo.value;
            const precio = parseFloat(precioTexto.replace('$', ''));
            const especificaciones = document.getElementById('especificaciones_equipo').value || '-';

            if (!tipoEquipoVal) {
                alert('❌ Seleccione un tipo de equipo');
                return;
            }

            if (!productoId) {
                alert('❌ Seleccione un producto');
                return;
            }

            if (isNaN(cantidad) || cantidad < 1) {
                alert('❌ Cantidad inválida');
                return;
            }

            const subtotal = cantidad * precio;

            carrito.push({
                tipo: 'Equipo',
                nombre: productoNombre,
                categoria: null,
                cantidad: cantidad,
                precio: precio,
                subtotal: subtotal,
                garantia: tieneGarantia,
                duracion_garantia: duracionTexto,
                especificaciones: especificaciones
            });

            calcularTotalGeneral();
            actualizarTabla();
            resetearFormularioEquipo();
            alert(`✅ ${productoNombre} agregado al carrito`);
        }

        function resetearFormularioEquipo() {
            tipoEquipoSelect.value = '';
            productoEquipo.innerHTML = '<option value="">Seleccione un tipo de equipo primero</option>';
            productoEquipo.disabled = true;
            cantidadEquipo.value = '1';
            garantiaSi.checked = true;
            duracionGroup.style.display = 'block';
            duracionEquipo.value = '1';
            precioBaseEquipo.value = '';
            precioFinalEquipo.value = '$0.00';
            totalEquipo.value = '$0.00';
            document.getElementById('especificaciones_equipo').value = '';
        }

        // ==================== TABLA DEL CARRITO ====================
        function actualizarTabla() {
            const tbody = document.getElementById('tabla_detalle');
            const contadorItems = document.getElementById('contador_items');

            if (carrito.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No hay productos agregados</td></tr>';
                contadorItems.textContent = '0';
                return;
            }

            let html = '';
            carrito.forEach((item, index) => {
                let categoria = item.tipo === 'Periférico' ? (item.categoria || 'N/A') : 'N/A';
                let garantia = item.tipo === 'Equipo' ? (item.garantia ? 'Sí' : 'No') : 'N/A';
                let duracion = (item.tipo === 'Equipo' && item.garantia && item.duracion_garantia) ? item.duracion_garantia : 'N/A';

                html += `
                <tr>
                    <td><strong>${item.nombre}</strong><br><small class="text-muted">(${item.tipo})</small></td>
                    <td class="${categoria === 'N/A' ? 'text-na' : ''}">${categoria}</td>
                    <td>$${item.precio.toFixed(2)}</td>
                    <td class="${garantia === 'N/A' ? 'text-na' : ''}">${garantia}</td>
                    <td>$${item.subtotal.toFixed(2)}</td>
                    <td><small>${item.especificaciones && item.especificaciones !== '-' ? (item.especificaciones.length > 30 ? item.especificaciones.substring(0, 30) + '...' : item.especificaciones) : '-'}</small></td>
                    <td class="${duracion === 'N/A' ? 'text-na' : ''}">${duracion}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            });

            tbody.innerHTML = html;
            contadorItems.textContent = carrito.length;
        }

        function eliminarProducto(index) {
            carrito.splice(index, 1);
            calcularTotalGeneral();
            actualizarTabla();
        }

        function calcularTotalGeneral() {
            totalVenta = carrito.reduce((sum, item) => sum + item.subtotal, 0);
            document.getElementById('total_venta').textContent = `$${totalVenta.toFixed(2)}`;
        }

        function cancelarVenta() {
            if (carrito.length > 0 && confirm('¿Seguro que desea cancelar la venta actual?')) {
                carrito = [];
                totalVenta = 0;
                actualizarTabla();
                calcularTotalGeneral();
            }
        }

        function actualizarFolio() {
            fetch('{{ route("ventas.nuevo-folio") }}')
                .then(response => response.json())
                .then(data => {
                    console.log('📝 Nuevo folio:', data.folio);
                    const folioInput = document.getElementById('folio_actual');
                    if (folioInput) {
                        folioInput.value = data.folio;
                    }
                    // Actualizar en el header
                    const folioSpan = document.querySelector('.empleado-info strong');
                    if (folioSpan) {
                        folioSpan.innerHTML = `No.Ticket: ${data.folio}`;
                    }
                })
                .catch(error => {
                    console.error('Error al obtener nuevo folio:', error);
                });
        }



        function procesarVenta(event) {
            console.log('🔵 Procesando venta...');
            console.log('Carrito:', carrito);
            console.log('Total:', totalVenta);

            if (carrito.length === 0) {
                alert('❌ Agregue al menos un producto');
                return;
            }

            const clienteNombre = document.getElementById('cliente_nombre').value.trim();
            const folioInput = document.getElementById('folio_actual');

            const ventaData = {
                folio: folioInput ? folioInput.value : '{{ $nuevoFolio }}',
                cliente_nombre: clienteNombre || 'Público en general',
                cliente_rfc: document.getElementById('cliente_rfc').value,
                cliente_telefono: document.getElementById('cliente_telefono').value,
                productos: carrito.map(item => ({
                    nombre: item.nombre,
                    cantidad: item.cantidad,
                    precio: item.precio,
                    garantia: item.garantia || false,
                    duracion_garantia: item.duracion_garantia || null,
                    especificaciones: item.especificaciones || ''
                })),
                total: totalVenta
            };

            console.log('Datos a enviar:', ventaData);

            const btnProcesar = event.target;
            const textoOriginal = btnProcesar.innerHTML;
            btnProcesar.disabled = true;
            btnProcesar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Procesando...';
            document.getElementById('loading_overlay').style.display = 'flex';

            fetch('{{ route("ventas.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(ventaData)
            })
                .then(response => {
                    console.log('Respuesta status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta data:', data);
                    document.getElementById('loading_overlay').style.display = 'none';
                    btnProcesar.disabled = false;
                    btnProcesar.innerHTML = textoOriginal;

                    if (data.success) {
                        // Descargar PDF del ticket
                        if (data.pdf_url) {
                            window.open(data.pdf_url, '_blank');
                        }
                        alert('✅ Venta #' + data.folio + ' registrada exitosamente');
                        actualizarFolio();
                        // Limpiar carrito
                        carrito = [];
                        totalVenta = 0;
                        actualizarTabla();
                        calcularTotalGeneral();
                        // Limpiar cliente
                        document.getElementById('cliente_nombre').value = '';
                        document.getElementById('cliente_rfc').value = '';
                        document.getElementById('cliente_telefono').value = '';
                    } else {
                        alert('❌ Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error en fetch:', error);
                    document.getElementById('loading_overlay').style.display = 'none';
                    btnProcesar.disabled = false;
                    btnProcesar.innerHTML = textoOriginal;
                    alert('❌ Error al procesar la venta: ' + error.message);
                });
        }

        // ==================== CARGAR HISTORIAL DE VENTAS DEL USUARIO ====================
        function cargarHistorial() {
            fetch('{{ route("ventas.mis-ventas") }}')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('tabla_historial');
                    const totalHistorial = document.getElementById('total_historial');

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No hay ventas registradas</td></tr>';
                        totalHistorial.textContent = '0';
                        return;
                    }

                    let html = '';
                    data.forEach(venta => {
                        venta.detalles.forEach(detalle => {
                            let categoria = detalle.item_type === 'producto' ? (detalle.producto?.categoria || 'N/A') : 'N/A';
                            let garantia = detalle.garantia ? 'Sí' : 'No';
                            let duracion = detalle.duracion_garantia || 'N/A';

                            html += `
                        <tr>
                            <td><strong>${detalle.producto?.nombre || 'Producto'}</strong><br><small class="text-muted">Folio: ${venta.folio}</small></td>
                            <td>${categoria}</td>
                            <td>$${parseFloat(detalle.precio_unitario).toFixed(2)}</td>
                            <td>${garantia}</td>
                            <td>$${parseFloat(detalle.subtotal).toFixed(2)}</td>
                            <td><small>${detalle.especificaciones ? (detalle.especificaciones.length > 20 ? detalle.especificaciones.substring(0, 20) + '...' : detalle.especificaciones) : '-'}</small></td>
                            <td>${duracion}</td>
                            <td>
                                <a href="{{ url('/ventas/ticket') }}/${venta.idventa}" class="btn btn-info btn-sm" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                        });
                    });

                    tbody.innerHTML = html;
                    totalHistorial.textContent = data.length;
                })
                .catch(error => {
                    console.error('Error al cargar historial:', error);
                    document.getElementById('tabla_historial').innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error al cargar historial</td></tr>';
                });
        }

        // Cargar historial al iniciar la página
        document.addEventListener('DOMContentLoaded', function() {
            cargarHistorial();
        });
    </script>
@endpush
