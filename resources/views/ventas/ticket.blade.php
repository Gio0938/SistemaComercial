<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $venta->folio }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
            max-width: 300px;
            margin: 0 auto;
        }
        .ticket {
            border: 1px dashed #ccc;
            padding: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header h2 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .info {
            margin-bottom: 15px;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 10px;
        }
        .info p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 5px 0;
        }
        th {
            border-bottom: 1px dashed #000;
        }
        td:last-child,
        th:last-child {
            text-align: right;
        }
        .totales {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
            text-align: right;
        }
        .totales p {
            margin: 3px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #000;
            font-size: 10px;
        }
        .btn-print {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 20px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn-print:hover {
            background: #1a252f;
        }
        @media print {
            .btn-print {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
            }
            .ticket {
                border: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
<div class="ticket">
    <div class="header">
        <h2>🏪 GESTIÓN COMERCIAL</h2>
        <p>Ticket de Venta</p>
        <p><strong>Folio:</strong> {{ $venta->folio }}</p>
        <p>{{ $venta->created_at->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info">
        <p><strong>Atendió:</strong> {{ $venta->usuario->name ?? 'N/A' }}</p>
        <p><strong>Cliente:</strong> {{ $venta->cliente->nombre ?? 'Público en general' }}</p>
        @if(isset($venta->cliente->rfc) && $venta->cliente->rfc)
            <p><strong>RFC:</strong> {{ $venta->cliente->rfc }}</p>
        @endif
    </div>

    <table>
        <thead>
        <tr>
            <th>Cant</th>
            <th>Producto</th>
            <th>Precio</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @forelse($venta->detalles as $detalle)
            <tr>
                <td>{{ $detalle->cantidad }}</td>
                <td>{{ $detalle->producto->nombre ?? 'Producto' }}</td>
                <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                <td>${{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align: center;">No hay productos</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="totales">
        <p><strong>Subtotal:</strong> ${{ number_format($venta->subtotal, 2) }}</p>
        <p><strong>IVA (16%):</strong> ${{ number_format($venta->iva, 2) }}</p>
        <p><strong>TOTAL:</strong> ${{ number_format($venta->total, 2) }}</p>
    </div>

    <div class="footer">
        <p>¡Gracias por su compra!</p>
        <p>📍 Av. Principal #123, Ciudad</p>
        <p>📞 Tel: (228) 123-4567</p>
    </div>
</div>

<button class="btn-print" onclick="window.print();window.close();">
    🖨️ Imprimir Ticket
</button>
</body>
</html>
