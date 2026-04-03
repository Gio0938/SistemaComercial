@extends('layouts.app')

@section('title', 'Nueva Orden de Servicio')

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

        .tipo-servicio-group {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .tipo-servicio-btn {
            flex: 1;
            text-align: center;
        }

        .tipo-servicio-btn input[type="radio"] {
            display: none;
        }

        .tipo-servicio-btn label {
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

        .tipo-servicio-btn label i {
            font-size: 2rem;
            display: block;
            margin-bottom: 8px;
        }

        .tipo-servicio-btn input[type="radio"]:checked + label {
            background: linear-gradient(135deg, var(--pos-success) 0%, #219a52 100%);
            color: white;
            border-color: var(--pos-success);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .panel-servicio {
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

        .empleado-info {
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 5px 10px;
        }

        .text-na {
            color: #95a5a6;
            font-style: italic;
        }

        .subtotal-text {
            font-size: 1.1rem;
            font-weight: bold;
            color: var(--pos-success);
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

        .badge-estado {
            font-size: 10px;
            padding: 3px 8px;
        }
        .bg-pendiente { background: #f39c12; color: white; }
        .bg-proceso { background: #3498db; color: white; }
        .bg-completado { background: #27ae60; color: white; }
        .bg-entregado { background: #2c3e50; color: white; }
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
                                    <i class="fas fa-tools me-2"></i>
                                    Gestión de Servicios Técnicos
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
                                            <strong>No. Orden: {{ $nuevoFolio }}</strong><br>
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
                            <i class="fas fa-user me-2"></i>Datos del Cliente
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label class="form-label small">Nombre *</label>
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
                            <div class="col-md-12 mb-2">
                                <label class="form-label small">Correo</label>
                                <input type="email" class="form-control" id="cliente_email" placeholder="correo@ejemplo.com">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datos del Equipo -->
                <div class="card pos-card">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="fas fa-laptop me-2"></i>Datos del Equipo</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="form-label small">Tipo de Equipo *</label>
                                <select class="form-select" id="equipo_tipo" required>
                                    <option value="">Seleccione un tipo...</option>
                                    <option value="Laptop">💻 Laptop</option>
                                    <option value="PC Escritorio">🖥️ PC Escritorio</option>
                                    <option value="Tablet">📱 Tablet</option>
                                    <option value="Impresora">🖨️ Impresora</option>
                                    <option value="Otro">🔧 Otro</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small">Marca *</label>
                                <select class="form-select" id="equipo_marca" disabled>
                                    <option value="">Primero seleccione un tipo de equipo</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small">Modelo *</label>
                                <select class="form-select" id="equipo_modelo" disabled>
                                    <option value="">Primero seleccione una marca</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small">No. Serie</label>
                                <input type="text" class="form-control" id="equipo_serie" placeholder="Número de serie">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small">Especificaciones</label>
                                <textarea class="form-control" id="especificaciones" rows="2" placeholder="Procesador, RAM, ROM, GPU..."></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="form-label small">Diagnóstico / Problemas</label>
                                <textarea class="form-control" id="diagnostico" rows="2" placeholder="Describa el problema del equipo..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tipo de Servicio - Radio Button -->
                <div class="card pos-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-tag me-2"></i>Tipo de Servicio
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="tipo-servicio-group">
                            <div class="tipo-servicio-btn">
                                <input type="radio" name="tipo_servicio" id="tipo_preventivo" value="preventivo" checked>
                                <label for="tipo_preventivo">
                                    <i class="fas fa-shield-alt"></i>
                                    Preventivo
                                </label>
                            </div>
                            <div class="tipo-servicio-btn">
                                <input type="radio" name="tipo_servicio" id="tipo_correctivo" value="correctivo">
                                <label for="tipo_correctivo">
                                    <i class="fas fa-tools"></i>
                                    Correctivo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel PREVENTIVO -->
                <div id="panel_preventivo" class="panel-servicio">
                    <div class="card pos-card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-shield-alt me-2"></i>Servicio Preventivo
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="form-field">
                                <label>Servicio <span class="required">*</span></label>
                                <select class="form-select" id="preventivo_servicio">
                                    <option value="Limpieza interna">Limpieza interna</option>
                                    <option value="Mantenimiento preventivo">Mantenimiento preventivo</option>
                                    <option value="Actualización de software">Actualización de software</option>
                                    <option value="Optimización de sistema">Optimización de sistema</option>
                                    <option value="Cambio de pasta térmica">Cambio de pasta térmica</option>
                                    <option value="Diagnóstico completo">Diagnóstico completo</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-field">
                                        <label>Costo por Hora (HR)</label>
                                        <input type="number" class="form-control" id="preventivo_costo" value="40" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-field">
                                        <label>Horas</label>
                                        <input type="number" class="form-control" id="preventivo_horas" value="1" step="0.5">
                                    </div>
                                </div>
                            </div>

                            <div class="form-field">
                                <label>Diagnóstico / Observaciones</label>
                                <textarea class="form-control" id="preventivo_diagnostico" rows="2" placeholder="Describa el diagnóstico..."></textarea>
                            </div>

                            <div class="form-field">
                                <label>Subtotal</label>
                                <input type="text" class="form-control subtotal-text" id="preventivo_subtotal" readonly value="$0.00">
                            </div>

                            <button class="btn-agregar" onclick="agregarServicio('preventivo')">
                                <i class="fas fa-cart-plus me-2"></i> AGREGAR
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Panel CORRECTIVO -->
                <div id="panel_correctivo" class="panel-servicio" style="display: none;">
                    <div class="card pos-card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-tools me-2"></i>Servicio Correctivo
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="form-field">
                                <label>Servicio <span class="required">*</span></label>
                                <select class="form-select" id="correctivo_servicio">
                                    <option value="Reemplazar pantalla">Reemplazar pantalla</option>
                                    <option value="Reemplazar batería">Reemplazar batería</option>
                                    <option value="Reparación placa madre">Reparación placa madre</option>
                                    <option value="Reemplazar teclado">Reemplazar teclado</option>
                                    <option value="Reemplazar disco duro">Reemplazar disco duro</option>
                                    <option value="Reemplazar memoria RAM">Reemplazar memoria RAM</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-field">
                                        <label>Costo por Hora (HR)</label>
                                        <input type="number" class="form-control" id="correctivo_costo" value="85" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-field">
                                        <label>Horas</label>
                                        <input type="number" class="form-control" id="correctivo_horas" value="1.5" step="0.5">
                                    </div>
                                </div>
                            </div>

                            <div class="form-field">
                                <label>Refacción</label>
                                <input type="text" class="form-control" id="correctivo_refaccion" placeholder="Nombre de la refacción">
                            </div>

                            <div class="form-field">
                                <label>Costo Refacción</label>
                                <input type="number" class="form-control" id="correctivo_costo_refaccion" value="0" step="0.01">
                            </div>

                            <div class="form-field">
                                <label>Diagnóstico / Observaciones</label>
                                <textarea class="form-control" id="correctivo_diagnostico" rows="2" placeholder="Describa el diagnóstico..."></textarea>
                            </div>

                            <div class="form-field">
                                <label>Subtotal</label>
                                <input type="text" class="form-control subtotal-text" id="correctivo_subtotal" readonly value="$0.00">
                            </div>

                            <button class="btn-agregar" onclick="agregarServicio('correctivo')">
                                <i class="fas fa-cart-plus me-2"></i> AGREGAR
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Derecho - Carrito -->
            <div class="col-md-7">
                <div class="card pos-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Servicios Agregados
                            <span class="badge bg-light text-dark ms-2" id="contador_items">0</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="tabla-detalle">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Servicio</th>
                                    <th>Costo HR</th>
                                    <th>Horas</th>
                                    <th>Refacción</th>
                                    <th>Costo Ref.</th>
                                    <th>Subtotal</th>
                                    <th width="30"></th>
                                </thead>
                                </thead>
                                <tbody id="tabla_detalle">
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-tools fa-2x mb-2 d-block"></i>
                                        No hay servicios agregados
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="total-box">
                                    <div class="total-amount" id="total_servicio">$0.00</div>
                                    <small>Total de la orden</small>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <button class="btn btn-success w-100 py-2" onclick="guardarOrden()">
                                    <i class="fas fa-save me-1"></i> GUARDAR ORDEN
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-danger w-100 py-2" onclick="cancelarOrden()">
                                    <i class="fas fa-trash-alt me-1"></i> CANCELAR
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========== HISTORIAL DE ÓRDENES DE SERVICIO (DEBAJO) ========== -->
                <div class="card pos-card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            Historial de Órdenes - {{ Auth::user()->name }}
                            <span class="badge bg-light text-dark ms-2" id="total_historial">0</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="tabla-detalle" style="max-height: 300px;">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Equipo</th>
                                    <th>Servicios</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th width="80">Opciones</th>
                                </tr>
                                </thead>
                                <tbody id="tabla_historial_ordenes">
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Cargando historial...</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Campo oculto para el folio -->
                <input type="hidden" id="folio_actual" value="{{ $nuevoFolio }}">

                <div class="loading-overlay" id="loading_overlay">
                    <div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>

    <!-- Campo oculto para el folio -->
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
        let totalOrden = 0;

        // Elementos DOM
        const tipoPreventivo = document.getElementById('tipo_preventivo');
        const tipoCorrectivo = document.getElementById('tipo_correctivo');
        const panelPreventivo = document.getElementById('panel_preventivo');
        const panelCorrectivo = document.getElementById('panel_correctivo');

        // Cambiar paneles
        tipoPreventivo.addEventListener('change', function() {
            if (this.checked) {
                panelPreventivo.style.display = 'block';
                panelCorrectivo.style.display = 'none';
            }
        });

        tipoCorrectivo.addEventListener('change', function() {
            if (this.checked) {
                panelPreventivo.style.display = 'none';
                panelCorrectivo.style.display = 'block';
            }
        });

        // ==================== PREVENTIVO ====================
        function calcularSubtotalPreventivo() {
            const costo = parseFloat(document.getElementById('preventivo_costo').value) || 0;
            const horas = parseFloat(document.getElementById('preventivo_horas').value) || 0;
            const subtotal = costo * horas;
            document.getElementById('preventivo_subtotal').value = `$${subtotal.toFixed(2)}`;
            return subtotal;
        }

        document.getElementById('preventivo_costo').addEventListener('input', calcularSubtotalPreventivo);
        document.getElementById('preventivo_horas').addEventListener('input', calcularSubtotalPreventivo);

        // ==================== CORRECTIVO ====================
        function calcularSubtotalCorrectivo() {
            const costo = parseFloat(document.getElementById('correctivo_costo').value) || 0;
            const horas = parseFloat(document.getElementById('correctivo_horas').value) || 0;
            const costoRefaccion = parseFloat(document.getElementById('correctivo_costo_refaccion').value) || 0;
            const subtotal = (costo * horas) + costoRefaccion;
            document.getElementById('correctivo_subtotal').value = `$${subtotal.toFixed(2)}`;
            return subtotal;
        }

        document.getElementById('correctivo_costo').addEventListener('input', calcularSubtotalCorrectivo);
        document.getElementById('correctivo_horas').addEventListener('input', calcularSubtotalCorrectivo);
        document.getElementById('correctivo_costo_refaccion').addEventListener('input', calcularSubtotalCorrectivo);

        // ==================== AGREGAR SERVICIO ====================
        function agregarServicio(tipo) {
            let servicio = {};

            if (tipo === 'preventivo') {
                const servicioNombre = document.getElementById('preventivo_servicio').value;
                const costo = parseFloat(document.getElementById('preventivo_costo').value) || 0;
                const horas = parseFloat(document.getElementById('preventivo_horas').value) || 0;
                const diagnostico = document.getElementById('preventivo_diagnostico').value;
                const subtotal = costo * horas;

                if (!servicioNombre) {
                    alert('❌ Seleccione un servicio');
                    return;
                }

                if (horas <= 0) {
                    alert('❌ Las horas deben ser mayores a 0');
                    return;
                }

                servicio = {
                    tipo: 'Preventivo',
                    servicio_nombre: servicioNombre,
                    costo_hr: costo,
                    horas: horas,
                    refaccion_nombre: null,
                    costo_refaccion: 0,
                    diagnostico: diagnostico,
                    subtotal: subtotal
                };

                // Limpiar campos
                document.getElementById('preventivo_diagnostico').value = '';
                document.getElementById('preventivo_horas').value = '1';
                calcularSubtotalPreventivo();

            } else {
                const servicioNombre = document.getElementById('correctivo_servicio').value;
                const costo = parseFloat(document.getElementById('correctivo_costo').value) || 0;
                const horas = parseFloat(document.getElementById('correctivo_horas').value) || 0;
                const refaccion = document.getElementById('correctivo_refaccion').value;
                const costoRefaccion = parseFloat(document.getElementById('correctivo_costo_refaccion').value) || 0;
                const diagnostico = document.getElementById('correctivo_diagnostico').value;
                const subtotal = (costo * horas) + costoRefaccion;

                if (!servicioNombre) {
                    alert('❌ Seleccione un servicio');
                    return;
                }

                if (horas <= 0) {
                    alert('❌ Las horas deben ser mayores a 0');
                    return;
                }

                servicio = {
                    tipo: 'Correctivo',
                    servicio_nombre: servicioNombre,
                    costo_hr: costo,
                    horas: horas,
                    refaccion_nombre: refaccion || null,
                    costo_refaccion: costoRefaccion,
                    diagnostico: diagnostico,
                    subtotal: subtotal
                };

                // Limpiar campos
                document.getElementById('correctivo_diagnostico').value = '';
                document.getElementById('correctivo_refaccion').value = '';
                document.getElementById('correctivo_costo_refaccion').value = '0';
                document.getElementById('correctivo_horas').value = '1.5';
                calcularSubtotalCorrectivo();
            }

            carrito.push(servicio);
            actualizarTabla();
            alert(`✅ ${servicio.servicio_nombre} agregado al carrito`);
        }

        // ==================== TABLA DEL CARRITO ====================
        function actualizarTabla() {
            const tbody = document.getElementById('tabla_detalle');
            const contadorItems = document.getElementById('contador_items');
            let total = 0;

            if (carrito.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No hay servicios agregados</td></tr>';
                contadorItems.textContent = '0';
                document.getElementById('total_servicio').textContent = '$0.00';
                return;
            }

            let html = '';
            carrito.forEach((item, index) => {
                total += item.subtotal;
                const badgeClass = item.tipo === 'Preventivo' ? 'bg-success' : 'bg-danger';

                html += `
                <tr>
                    <td><span class="badge ${badgeClass}">${item.tipo}</span></td>
                    <td>
                        <strong>${item.servicio_nombre}</strong>
                        ${item.diagnostico ? '<br><small class="text-muted">' + item.diagnostico.substring(0, 40) + '</small>' : ''}
                    </td>
                    <td>$${item.costo_hr.toFixed(2)}</td>
                    <td>${item.horas}</td>
                    <td>${item.refaccion_nombre || '---'}</td>
                    <td>$${item.costo_refaccion.toFixed(2)}</td>
                    <td>$${item.subtotal.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="eliminarServicio(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            });

            tbody.innerHTML = html;
            contadorItems.textContent = carrito.length;
            document.getElementById('total_servicio').textContent = `$${total.toFixed(2)}`;
        }

        function eliminarServicio(index) {
            carrito.splice(index, 1);
            actualizarTabla();
        }

        // ==================== DATOS DEL EQUIPO DINÁMICOS ====================
        const equipoTipo = document.getElementById('equipo_tipo');
        const equipoMarca = document.getElementById('equipo_marca');
        const equipoModelo = document.getElementById('equipo_modelo');

        // Cuando cambia el tipo de equipo, cargar marcas
        equipoTipo.addEventListener('change', function() {
            const tipo = this.value;

            if (!tipo) {
                equipoMarca.innerHTML = '<option value="">Primero seleccione un tipo de equipo</option>';
                equipoMarca.disabled = true;
                equipoModelo.innerHTML = '<option value="">Primero seleccione una marca</option>';
                equipoModelo.disabled = true;
                return;
            }

            // Mostrar loading
            equipoMarca.innerHTML = '<option value="">Cargando marcas...</option>';
            equipoMarca.disabled = true;

            fetch(`/get-marcas-por-tipo?tipo_equipo=${encodeURIComponent(tipo)}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    equipoMarca.innerHTML = '<option value="">Seleccione una marca...</option>';

                    data.forEach(marca => {
                        equipoMarca.innerHTML += `<option value="${marca.idmarca}">${marca.nombre}</option>`;
                    });

                    equipoMarca.disabled = false;
                    equipoModelo.innerHTML = '<option value="">Primero seleccione una marca</option>';
                    equipoModelo.disabled = true;
                })
                .catch(error => {
                    console.error('Error al cargar marcas:', error);
                    equipoMarca.innerHTML = '<option value="">Error al cargar marcas</option>';
                });
        });

        // Cuando cambia la marca, cargar modelos
        equipoMarca.addEventListener('change', function() {
            const idmarca = this.value;

            if (!idmarca) {
                equipoModelo.innerHTML = '<option value="">Primero seleccione una marca</option>';
                equipoModelo.disabled = true;
                return;
            }

            // Mostrar loading
            equipoModelo.innerHTML = '<option value="">Cargando modelos...</option>';
            equipoModelo.disabled = true;

            fetch(`/get-modelos-por-marca?idmarca=${idmarca}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    equipoModelo.innerHTML = '<option value="">Seleccione un modelo...</option>';

                    data.forEach(modelo => {
                        equipoModelo.innerHTML += `<option value="${modelo.idmodelo}">${modelo.nombre}</option>`;
                    });

                    equipoModelo.disabled = false;
                })
                .catch(error => {
                    console.error('Error al cargar modelos:', error);
                    equipoModelo.innerHTML = '<option value="">Error al cargar modelos</option>';
                });
        });

        // ==================== GUARDAR ORDEN ====================
        function guardarOrden() {
            if (carrito.length === 0) {
                alert('❌ Agregue al menos un servicio a la orden');
                return;
            }

            const clienteNombre = document.getElementById('cliente_nombre').value.trim();
            if (!clienteNombre) {
                alert('❌ Ingrese el nombre del cliente');
                return;
            }

            // Obtener los valores seleccionados de marca y modelo
            const marcaSelect = document.getElementById('equipo_marca');
            const modeloSelect = document.getElementById('equipo_modelo');

            const marcaNombre = marcaSelect.options[marcaSelect.selectedIndex]?.text || '';
            const modeloNombre = modeloSelect.options[modeloSelect.selectedIndex]?.text || '';

            const ordenData = {
                folio: document.getElementById('folio_actual').value,
                cliente_nombre: clienteNombre,
                cliente_rfc: document.getElementById('cliente_rfc').value,
                cliente_telefono: document.getElementById('cliente_telefono').value,
                cliente_email: document.getElementById('cliente_email').value,
                equipo_tipo: document.getElementById('equipo_tipo').value,
                equipo_marca: marcaNombre,  // Guardamos el nombre de la marca
                equipo_marca_id: marcaSelect.value,  // Guardamos también el ID si lo necesitas
                equipo_modelo: modeloNombre,  // Guardamos el nombre del modelo
                equipo_modelo_id: modeloSelect.value,  // Guardamos también el ID
                equipo_serie: document.getElementById('equipo_serie').value,
                especificaciones: document.getElementById('especificaciones').value,
                diagnostico: document.getElementById('diagnostico').value,
                detalles: carrito,
                total: carrito.reduce((sum, item) => sum + item.subtotal, 0)
            };

            // Validar que se haya seleccionado marca y modelo (opcional)
            if (document.getElementById('equipo_tipo').value && !marcaNombre) {
                alert('⚠️ Por favor seleccione una marca para el equipo');
                return;
            }

            if (marcaNombre && !modeloNombre) {
                alert('⚠️ Por favor seleccione un modelo para el equipo');
                return;
            }

            console.log('Datos a enviar:', ordenData); // Para depuración

            document.getElementById('loading_overlay').style.display = 'flex';

            fetch('{{ route("ordenes.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(ordenData)
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading_overlay').style.display = 'none';
                    if (data.success) {
                        alert('✅ Orden de servicio guardada exitosamente');

                        // Limpiar carrito
                        carrito = [];
                        actualizarTabla();

                        // Limpiar campos del cliente
                        document.getElementById('cliente_nombre').value = '';
                        document.getElementById('cliente_rfc').value = '';
                        document.getElementById('cliente_telefono').value = '';
                        document.getElementById('cliente_email').value = '';

                        // Limpiar campos del equipo
                        document.getElementById('equipo_tipo').value = '';
                        document.getElementById('equipo_marca').innerHTML = '<option value="">Primero seleccione un tipo de equipo</option>';
                        document.getElementById('equipo_marca').disabled = true;
                        document.getElementById('equipo_modelo').innerHTML = '<option value="">Primero seleccione una marca</option>';
                        document.getElementById('equipo_modelo').disabled = true;
                        document.getElementById('equipo_serie').value = '';
                        document.getElementById('especificaciones').value = '';
                        document.getElementById('diagnostico').value = '';

                        // Recargar historial
                        cargarHistorial();

                        // Actualizar folio
                        actualizarFolio();
                    } else {
                        alert('❌ Error: ' + data.message);
                    }
                })
                .catch(error => {
                    document.getElementById('loading_overlay').style.display = 'none';
                    console.error('Error:', error);
                    alert('❌ Error al guardar la orden: ' + error.message);
                });
        }

        function cancelarOrden() {
            if (carrito.length > 0 && confirm('¿Seguro que desea cancelar la orden actual?')) {
                carrito = [];
                actualizarTabla();
            } else if (carrito.length === 0) {
                alert('No hay servicios en el carrito');
            }
        }

        // ==================== HISTORIAL DE ÓRDENES ====================
        function cargarHistorialOrdenes() {
            console.log('🔵 Cargando historial de órdenes...');

            fetch('/mis-ordenes', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos recibidos:', data);

                    const tbody = document.getElementById('tabla_historial_ordenes');
                    const totalHistorial = document.getElementById('total_historial');

                    if (!data || data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No hay órdenes registradas</td></tr>';
                        totalHistorial.textContent = '0';
                        return;
                    }

                    let html = '';
                    data.forEach(orden => {
                        let estadoClass = '';
                        let estadoIcono = '';
                        switch(orden.estado) {
                            case 'Pendiente':
                                estadoClass = 'bg-warning';
                                estadoIcono = 'fa-clock';
                                break;
                            case 'En Proceso':
                                estadoClass = 'bg-info';
                                estadoIcono = 'fa-spinner';
                                break;
                            case 'Completado':
                                estadoClass = 'bg-success';
                                estadoIcono = 'fa-check-circle';
                                break;
                            case 'Entregado':
                                estadoClass = 'bg-primary';
                                estadoIcono = 'fa-truck';
                                break;
                            default:
                                estadoClass = 'bg-secondary';
                                estadoIcono = 'fa-question';
                        }

                        const fecha = new Date(orden.fecha).toLocaleDateString('es-MX');
                        const total = parseFloat(orden.total).toFixed(2);
                        const serviciosCount = orden.detalles_count || 0;

                        html += `
                <tr>
                    <td><strong>#${orden.folio}</strong></td>
                    <td>${fecha}</td>
                    <td>${orden.cliente_nombre || 'N/A'}</td>
                    <td>${orden.equipo_tipo || 'N/A'} ${orden.equipo_marca ? '-' + orden.equipo_marca : ''}</td>
                    <td><span class="badge bg-secondary">${serviciosCount}</span> servicio(s)</td>
                    <td><strong>$${total}</strong></td>
                    <td><span class="badge ${estadoClass}"><i class="fas ${estadoIcono} me-1"></i>${orden.estado || 'N/A'}</span></td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="/ordenes/${orden.idorden}" class="btn btn-info btn-sm" target="_blank" title="Ver Orden">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarOrden(${orden.idorden})" title="Eliminar Orden">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
                    });

                    tbody.innerHTML = html;
                    totalHistorial.textContent = data.length;
                })
                .catch(error => {
                    console.error('Error al cargar historial:', error);
                    document.getElementById('tabla_historial_ordenes').innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error al cargar historial</td></tr>';
                });
        }

        function eliminarOrden(ordenId) {
            if (confirm('¿Estás seguro de eliminar esta orden de servicio? Esta acción no se puede deshacer.')) {
                console.log('🗑️ Eliminando orden:', ordenId);

                fetch(`/ordenes/${ordenId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('✅ Orden eliminada exitosamente');
                            cargarHistorialOrdenes(); // Recargar historial
                        } else {
                            alert('❌ Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('❌ Error al eliminar la orden');
                    });
            }
        }

        function actualizarFolio() {
            fetch('{{ route("ordenes.nuevo-folio") }}')
                .then(response => response.json())
                .then(data => {
                    const folioInput = document.getElementById('folio_actual');
                    if (folioInput) {
                        folioInput.value = data.folio;
                    }
                    const folioSpan = document.querySelector('.empleado-info strong');
                    if (folioSpan) {
                        folioSpan.innerHTML = `No. Orden: ${data.folio}`;
                    }
                })
                .catch(error => {
                    console.error('Error al obtener nuevo folio:', error);
                });
        }

        // Cargar historial al iniciar la página
        document.addEventListener('DOMContentLoaded', function() {
            cargarHistorialOrdenes();  // Cambia el nombre si es diferente
            calcularSubtotalPreventivo();
            calcularSubtotalCorrectivo();
        });
    </script>
@endpush
