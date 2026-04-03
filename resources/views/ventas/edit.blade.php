@extends('layouts.app')

@section('title', 'Editar Venta #' . $venta->folio)

@push('styles')
    <style>
        .producto-row {
            transition: all 0.3s;
        }
        .producto-row:hover {
            background-color: #f8f9fa;
        }
        .btn-eliminar-producto {
            opacity: 0.7;
        }
        .btn-eliminar-producto:hover {
            opacity: 1;
        }
        .nuevo-producto-row {
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
                    <i class="fas fa-edit me-2"></i>Editar Venta #{{ $venta->folio }}
                </h3>
                <div class="card-tools">
                    <a href="{{ route('ventas.historial') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('ventas.update', $venta->idventa) }}" method="POST" id="formEditarVenta">
                    @csrf
                    @method('PUT')

                    <!-- Datos de la Venta -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estado de la Venta</label>
                                <select name="estado" class="form-control">
                                    <option value="pendiente" {{ $venta->estado == 'pendiente' ? 'selected' : '' }}>⏰ Pendiente</option>
                                    <option value="completada" {{ $venta->estado == 'completada' ? 'selected' : '' }}>✅ Completada</option>
                                    <option value="cancelada" {{ $venta->estado == 'cancelada' ? 'selected' : '' }}>❌ Cancelada</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Empleado que atendió</label>
                                <select name="iduser" class="form-control">
                                    @foreach($empleados as $empleado)
                                        <option value="{{ $empleado->id }}" {{ $venta->iduser == $empleado->id ? 'selected' : '' }}>
                                            {{ $empleado->name }} ({{ ucfirst($empleado->rol) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de Venta</label>
                                <input type="text" class="form-control" value="{{ $venta->created_at->format('d/m/Y H:i') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Cliente -->
                    @if($venta->cliente)
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5><i class="fas fa-user me-2"></i>Datos del Cliente</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" name="cliente_nombre" class="form-control" value="{{ $venta->cliente->nombre }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>RFC</label>
                                    <input type="text" name="cliente_rfc" class="form-control" value="{{ $venta->cliente->rfc }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" name="cliente_telefono" class="form-control" value="{{ $venta->cliente->telefono }}">
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Productos Vendidos - Tabla editable -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5><i class="fas fa-box-open me-2"></i>Productos Vendidos</h5>
                                <button type="button" class="btn btn-success btn-sm" onclick="agregarFilaProducto()">
                                    <i class="fas fa-plus me-1"></i>Agregar Producto
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tablaProductos">
                                    <thead class="bg-dark text-white">
                                    <tr>
                                        <th>Producto</th>
                                        <th width="100">Cantidad</th>
                                        <th width="120">Precio Unitario</th>
                                        <th width="120">Subtotal</th>
                                        <th width="50"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbodyProductos">
                                    @foreach($venta->detalles as $detalle)
                                        <tr class="producto-row" data-id="{{ $detalle->iddetalle }}">
                                            <td>
                                                <select name="productos[{{ $detalle->iddetalle }}][idprod]" class="form-control producto-select" data-id="{{ $detalle->iddetalle }}" required>
                                                    <option value="">Seleccione un producto...</option>
                                                    @foreach($productos as $producto)
                                                        <option value="{{ $producto->idprod }}"
                                                                data-precio="{{ $producto->precio }}"
                                                            {{ $detalle->idprod == $producto->idprod ? 'selected' : '' }}>
                                                            {{ $producto->nombre }} - ${{ number_format($producto->precio, 2) }} (Stock: {{ $producto->stock }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="productos[{{ $detalle->iddetalle }}][cantidad]"
                                                       class="form-control cantidad-input text-center"
                                                       value="{{ $detalle->cantidad }}" min="1" step="1"
                                                       data-id="{{ $detalle->iddetalle }}">
                                            </td>
                                            <td>
                                                <input type="text" name="productos[{{ $detalle->iddetalle }}][precio]"
                                                       class="form-control precio-input text-end"
                                                       value="{{ $detalle->precio_unitario }}" readonly>
                                            </td>
                                            <td class="subtotal-cell text-end">
                                                ${{ number_format($detalle->subtotal, 2) }}
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm btn-eliminar-producto" onclick="eliminarFilaProducto(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                        <td class="text-end"><strong id="subtotal_venta">${{ number_format($venta->subtotal, 2) }}</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td colspan="3" class="text-end"><strong>IVA (16%):</strong></td>
                                        <td class="text-end"><strong id="iva_venta">${{ number_format($venta->iva, 2) }}</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr class="bg-primary text-white">
                                        <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                                        <td class="text-end"><strong id="total_venta">${{ number_format($venta->total, 2) }}</strong></td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Campos ocultos para productos eliminados -->
                    <input type="hidden" name="productos_eliminados" id="productos_eliminados" value="">

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Guardar Cambios
                        </button>
                        <a href="{{ route('ventas.historial') }}" class="btn btn-secondary">
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
        let productosDisponibles = @json($productos);
        let contadorNuevo = 0;
        let productosEliminados = [];

        // Actualizar subtotal cuando cambia cantidad o precio
        function actualizarSubtotal(row) {
            const cantidad = parseInt(row.querySelector('.cantidad-input').value) || 0;
            const precio = parseFloat(row.querySelector('.precio-input').value) || 0;
            const subtotal = cantidad * precio;
            row.querySelector('.subtotal-cell').textContent = '$' + subtotal.toFixed(2);
            actualizarTotales();
        }

        // Actualizar precios cuando cambia el producto seleccionado
        function actualizarPrecio(row) {
            const select = row.querySelector('.producto-select');
            const selectedOption = select.options[select.selectedIndex];
            const precio = selectedOption.getAttribute('data-precio');
            const precioInput = row.querySelector('.precio-input');

            if (precio) {
                precioInput.value = parseFloat(precio).toFixed(2);
                actualizarSubtotal(row);
            }
        }

        // Calcular todos los totales
        function actualizarTotales() {
            let subtotal = 0;
            const rows = document.querySelectorAll('#tbodyProductos tr');

            rows.forEach(row => {
                const subtotalText = row.querySelector('.subtotal-cell').textContent;
                const subtotalValue = parseFloat(subtotalText.replace('$', '')) || 0;
                subtotal += subtotalValue;
            });

            const iva = subtotal * 0.16;
            const total = subtotal + iva;

            document.getElementById('subtotal_venta').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('iva_venta').textContent = '$' + iva.toFixed(2);
            document.getElementById('total_venta').textContent = '$' + total.toFixed(2);

            // Animación
            document.getElementById('total_venta').classList.add('total-actualizado');
            setTimeout(() => {
                document.getElementById('total_venta').classList.remove('total-actualizado');
            }, 500);
        }

        // Agregar nueva fila de producto
        function agregarFilaProducto() {
            const tbody = document.getElementById('tbodyProductos');
            const newId = 'nuevo_' + (++contadorNuevo);

            const row = document.createElement('tr');
            row.className = 'producto-row nuevo-producto-row';
            row.setAttribute('data-nuevo', 'true');

            row.innerHTML = `
            <td>
                <select name="productos_nuevos[${newId}][idprod]" class="form-control producto-select" data-id="${newId}" required>
                    <option value="">Seleccione un producto...</option>
                    ${productosDisponibles.map(p => `<option value="${p.idprod}" data-precio="${p.precio}">${p.nombre} - $${p.precio} (Stock: ${p.stock})</option>`).join('')}
                </select>
            </td>
            <td>
                <input type="number" name="productos_nuevos[${newId}][cantidad]" class="form-control cantidad-input text-center" value="1" min="1" step="1">
            </td>
            <td>
                <input type="text" name="productos_nuevos[${newId}][precio]" class="form-control precio-input text-end" value="0.00" readonly>
            </td>
            <td class="subtotal-cell text-end">$0.00</td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm btn-eliminar-producto" onclick="eliminarFilaProducto(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

            tbody.appendChild(row);

            // Agregar event listeners
            const select = row.querySelector('.producto-select');
            const cantidadInput = row.querySelector('.cantidad-input');

            select.addEventListener('change', () => actualizarPrecio(row));
            cantidadInput.addEventListener('input', () => actualizarSubtotal(row));
        }

        // Eliminar fila de producto
        function eliminarFilaProducto(btn) {
            const row = btn.closest('tr');
            const idDetalle = row.getAttribute('data-id');

            if (confirm('¿Eliminar este producto de la venta?')) {
                if (idDetalle && !row.getAttribute('data-nuevo')) {
                    productosEliminados.push(idDetalle);
                    document.getElementById('productos_eliminados').value = JSON.stringify(productosEliminados);
                }
                row.remove();
                actualizarTotales();
            }
        }

        // Inicializar event listeners para filas existentes
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#tbodyProductos tr');
            rows.forEach(row => {
                const select = row.querySelector('.producto-select');
                const cantidadInput = row.querySelector('.cantidad-input');

                if (select) {
                    select.addEventListener('change', () => actualizarPrecio(row));
                }
                if (cantidadInput) {
                    cantidadInput.addEventListener('input', () => actualizarSubtotal(row));
                }
            });
        });
    </script>
@endpush
