<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Reporte de Ventas</h1>
    <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
    <p>Total de ventas: {{ $ventas->count() }}</p>
</div>

<table>
    <thead>
    <tr>
        <th>Folio</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Empleado</th>
        <th>Subtotal</th>
        <th>IVA</th>
        <th>Total</th>
    </thead>
    </thead>
    <tbody>
    @php $totalGeneral = 0; @endphp
    @forelse($ventas as $venta)
        <tr>
            <td>{{ $venta->folio }}</td>
            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $venta->cliente->nombre ?? 'Público' }}</td>
            <td>{{ $venta->usuario->name ?? 'N/A' }}</td>
            <td>${{ number_format($venta->subtotal, 2) }}</td>
            <td>${{ number_format($venta->iva, 2) }}</td>
            <td>${{ number_format($venta->total, 2) }}</td>
        </tr>
        @php $totalGeneral += $venta->total; @endphp
    @empty
        <tr>
            <td colspan="7" style="text-align: center;">No hay ventas registradas</td>
        </tr>
    @endforelse
    </tbody>
    @if($ventas->count() > 0)
        <tfoot>
        <tr class="total-row">
            <td colspan="6" style="text-align: right;"><strong>TOTAL GENERAL:</strong></td>
            <td><strong>${{ number_format($totalGeneral, 2) }}</strong></td>
        </tr>
        </tfoot>
    @endif
</table>

<div class="footer">
    <p>Sistema de Gestión Comercial - Reporte generado automáticamente</p>
    <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
</div>
</body>
</html>
