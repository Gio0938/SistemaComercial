<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket {{ $venta->folio }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 15px;
        }
        .ticket {
            max-width: 280px;
            margin: 0 auto;
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
        .totales {
            border-top: 1px dashed #000;
            padding-top: 10px;
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #000;
            font-size: 10px;
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
    </div>

    <table>
        <thead>
        <tr><th>Cant</th><th>Producto</th><th>Precio</th><th>Total</th></tr>
        </thead>
        <tbody>
        @foreach($venta->detalles as $detalle)
            <tr>
                <td>{{ $detalle->cantidad }}</td>
                <td>{{ $detalle->producto->nombre ?? 'Producto' }}</td>
                <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                <td>${{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="totales">
        <p><strong>Subtotal:</strong> ${{ number_format($venta->subtotal, 2) }}</p>
        <p><strong>IVA (16%):</strong> ${{ number_format($venta->iva, 2) }}</p>
        <p><strong>TOTAL:</strong> ${{ number_format($venta->total, 2) }}</p>
    </div>

    <div class="footer">
        <p>¡Gracias por su compra!</p>
        <p>📍 Av. Principal #123 | 📞 (228) 123-4567</p>
    </div>
</div>
</body>
</html>
