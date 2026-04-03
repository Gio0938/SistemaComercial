@extends('layouts.app')

@section('title', 'Editar Orden #' . $orden->folio)

@push('styles')
    <style>
        .servicio-row {
            transition: all 0.3s;
        }
        .servicio-row:hover {
            background-color: #f8f9fa;
        }
        .btn-eliminar-servicio {
            opacity: 0.7;
        }
        .btn-eliminar-servicio:hover {
            opacity: 1;
        }
        .nuevo-servicio-row {
            background-color: #e8f5e9;
        }
        .total-actualizado {
            animation: pulse 0.5s ease;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit me-2"></i>Editar Orden de Servicio #{{ $orden->folio }}
                </h3>
                <div class="card-tools">
                    <a href="{{ route('ordenes.show', $orden->idorden) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('ordenes.update', $orden->idorden) }}" method="POST" id="formEditarOrden">
                    @csrf
                    @method('PUT')

                    <!-- Datos de la Orden -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estado de la Orden</label>
                                <select name="estado" class="form-control">
                                    <option value="Pendiente" {{ $orden->estado == 'Pendiente' ? 'selected' : '' }}>⏰ Pendiente</option>
                                    <option value="En Proceso" {{ $orden->estado == 'En Proceso' ? 'selected' : '' }}>🔄 En Proceso</option>
                                    <option value="Completado" {{ $orden->estado == 'Completado' ? 'selected' : '' }}>✅ Completado</option>
                                    <option value="Entregado" {{ $orden->estado == 'Entregado' ? 'selected' : '' }}>🚚 Entregado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Técnico que atendió</label>
                                <select name="tecnico_nombre" class="form-control">
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->name }}" {{ $orden->tecnico_nombre == $usuario->name ? 'selected' : '' }}>
                                            {{ $usuario->name }} ({{ ucfirst($usuario->rol) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de Orden</label>
                                <input type="text" class="form-control" value="{{ $orden->created_at->format('d/m/Y H:i') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Cliente -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5><i class="fas fa-user me-2"></i>Datos del Cliente</h5>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="cliente_nombre" class="form-control" value="{{ $orden->cliente_nombre }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>RFC</label>
                                <input type="text" name="cliente_rfc" class="form-control" value="{{ $orden->cliente_rfc }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="cliente_telefono" class="form-control" value="{{ $orden->cliente_telefono }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Correo</label>
                                <input type="email" name="cliente_email" class="form-control" value="{{ $orden->cliente_email }}">
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Equipo -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5><i class="fas fa-laptop me-2"></i>Datos del Equipo</h5>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo de Equipo</label>
                                <select name="equipo_tipo" class="form-control">
                                    <option value="Laptop" {{ $orden->equipo_tipo == 'Laptop' ? 'selected' : '' }}>💻 Laptop</option>
                                    <option value="PC Escritorio" {{ $orden->equipo_tipo == 'PC Escritorio' ? 'selected' : '' }}>🖥️ PC Escritorio</option>
                                    <option value="Tablet" {{ $orden->equipo_tipo == 'Tablet' ? 'selected' : '' }}>📱 Tablet</option>
                                    <option value="Impresora" {{ $orden->equipo_tipo == 'Impresora' ? 'selected' : '' }}>🖨️ Impresora</option>
                                    <option value="Otro" {{ $orden->equipo_tipo == 'Otro' ? 'selected' : '' }}>🔧 Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Marca</label>
                                <input type="text" name="equipo_marca" class="form-control" value="{{ $orden->equipo_marca }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Modelo</label>
                                <input type="text" name="equipo_modelo" class="form-control" value="{{ $orden->equipo_modelo }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. Serie</label>
                                <input type="text" name="equipo_serie" class="form-control" value="{{ $orden->equipo_serie }}">
                            </div>
                        </div>
                    </div>

                    <!-- Especificaciones y Diagnóstico -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Especificaciones</label>
                                <textarea name="especificaciones" class="form-control" rows="2">{{ $orden->especificaciones }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Diagnóstico / Problemas</label>
                                <textarea name="diagnostico" class="form-control" rows="2">{{ $orden->diagnostico }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Servicios - Tabla editable -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5><i class="fas fa-tools me-2"></i>Servicios Realizados</h5>
                                <button type="button" class="btn btn-success btn-sm" onclick="agregarFilaServicio()">
                                    <i class="fas fa-plus me-1"></i>Agregar Servicio
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablaServicios">
                                    <thead class="bg-dark text-white">
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Servicio</th>
                                        <th width="100">Costo HR</th>
                                        <th width="80">Horas</th>
                                        <th>Refacción</th>
                                        <th width="100">Costo Refacción</th>
                                        <th width="120">Subtotal</th>
                                        <th width="50"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbodyServicios">
                                    @foreach($orden->detalles as $detalle)
                                        <tr class="servicio-row" data-id="{{ $detalle->iddetalle }}">
                                            <td>
                                                <select name="servicios[{{ $detalle->iddetalle }}][tipo]" class="form-control tipo-select">
                                                    <option value="preventivo" {{ $detalle->tipo == 'preventivo' ? 'selected' : '' }}>🛡️ Preventivo</option>
                                                    <option value="correctivo" {{ $detalle->tipo == 'correctivo' ? 'selected' : '' }}>🔧 Correctivo</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="servicios[{{ $detalle->iddetalle }}][servicio_nombre]"
                                                       class="form-control" value="{{ $detalle->servicio_nombre }}">
                                            </td>
                                            <td>
                                                <input type="number" name="servicios[{{ $detalle->iddetalle }}][costo_hr]"
                                                       class="form-control costo-hr-input text-end"
                                                       value="{{ $detalle->costo_hr }}" step="0.01">
                                            </td>
                                            <td>
                                                <input type="number" name="servicios[{{ $detalle->iddetalle }}][horas]"
                                                       class="form-control horas-input text-center"
                                                       value="{{ $detalle->horas }}" step="0.5">
                                            </td>
                                            <td>
                                                <input type="text" name="servicios[{{ $detalle->iddetalle }}][refaccion_nombre]"
                                                       class="form-control" value="{{ $detalle->refaccion_nombre }}">
                                            </td>
                                            <td>
                                                <input type="number" name="servicios[{{ $detalle->iddetalle }}][costo_refaccion]"
                                                       class="form-control costo-refaccion-input text-end"
                                                       value="{{ $detalle->costo_refaccion }}" step="0.01">
                                            </td>
                                            <td class="subtotal-cell text-end">
                                                ${{ number_format($detalle->subtotal, 2) }}
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm btn-eliminar-servicio" onclick="eliminarFilaServicio(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="6" class="text-end"><strong>TOTAL:</strong></td>
                                        <td class="text-end"><strong id="total_orden">${{ number_format($orden->total, 2) }}</strong></td>
                                        <td>\n                                            </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Campos ocultos para servicios eliminados -->
                    <input type="hidden" name="servicios_eliminados" id="servicios_eliminados" value="">

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Guardar Cambios
                        </button>
                        <a href="{{ route('ordenes.show', $orden->idorden) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let contadorNuevo = 0;
        let serviciosEliminados = [];

        // Calcular subtotal de una fila
        function calcularSubtotal(row) {
            const costoHr = parseFloat(row.querySelector('.costo-hr-input').value) || 0;
            const horas = parseFloat(row.querySelector('.horas-input').value) || 0;
            const costoRefaccion = parseFloat(row.querySelector('.costo-refaccion-input').value) || 0;
            const subtotal = (costoHr * horas) + costoRefaccion;
            row.querySelector('.subtotal-cell').textContent = '$' + subtotal.toFixed(2);
            return subtotal;
        }

        // Actualizar total general
        function actualizarTotal() {
            let total = 0;
            const rows = document.querySelectorAll('#tbodyServicios tr');

            rows.forEach(row => {
                const subtotalText = row.querySelector('.subtotal-cell').textContent;
                const subtotal = parseFloat(subtotalText.replace('$', '')) || 0;
                total += subtotal;
            });

            document.getElementById('total_orden').textContent = '$' + total.toFixed(2);

            // Animación
            document.getElementById('total_orden').classList.add('total-actualizado');
            setTimeout(() => {
                document.getElementById('total_orden').classList.remove('total-actualizado');
            }, 500);
        }

        // Agregar nueva fila de servicio
        function agregarFilaServicio() {
            const tbody = document.getElementById('tbodyServicios');
            const newId = 'nuevo_' + (++contadorNuevo);

            const row = document.createElement('tr');
            row.className = 'servicio-row nuevo-servicio-row';
            row.setAttribute('data-nuevo', 'true');

            row.innerHTML = `
            <td>
                <select name="servicios_nuevos[${newId}][tipo]" class="form-control tipo-select">
                    <option value="preventivo">🛡️ Preventivo</option>
                    <option value="correctivo">🔧 Correctivo</option>
                </select>
            </td>
            <td>
                <input type="text" name="servicios_nuevos[${newId}][servicio_nombre]" class="form-control" placeholder="Nombre del servicio" required>
            </td>
            <td>
                <input type="number" name="servicios_nuevos[${newId}][costo_hr]" class="form-control costo-hr-input text-end" value="0" step="0.01">
            </td>
            <td>
                <input type="number" name="servicios_nuevos[${newId}][horas]" class="form-control horas-input text-center" value="0" step="0.5">
            </td>
            <td>
                <input type="text" name="servicios_nuevos[${newId}][refaccion_nombre]" class="form-control" placeholder="Refacción (opcional)">
            </td>
            <td>
                <input type="number" name="servicios_nuevos[${newId}][costo_refaccion]" class="form-control costo-refaccion-input text-end" value="0" step="0.01">
            </td>
            <td class="subtotal-cell text-end">$0.00</td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm btn-eliminar-servicio" onclick="eliminarFilaServicio(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

            tbody.appendChild(row);

            // Agregar event listeners
            const inputs = row.querySelectorAll('.costo-hr-input, .horas-input, .costo-refaccion-input');
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    calcularSubtotal(row);
                    actualizarTotal();
                });
            });

            // Calcular subtotal inicial
            calcularSubtotal(row);
        }

        // Eliminar fila de servicio
        function eliminarFilaServicio(btn) {
            const row = btn.closest('tr');
            const idDetalle = row.getAttribute('data-id');

            if (confirm('¿Eliminar este servicio de la orden?')) {
                if (idDetalle && !row.getAttribute('data-nuevo')) {
                    serviciosEliminados.push(idDetalle);
                    document.getElementById('servicios_eliminados').value = JSON.stringify(serviciosEliminados);
                }
                row.remove();
                actualizarTotal();
            }
        }

        // Inicializar event listeners para filas existentes
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#tbodyServicios tr');
            rows.forEach(row => {
                const inputs = row.querySelectorAll('.costo-hr-input, .horas-input, .costo-refaccion-input');
                inputs.forEach(input => {
                    input.addEventListener('input', () => {
                        calcularSubtotal(row);
                        actualizarTotal();
                    });
                });
            });
        });
    </script>
@endpush
