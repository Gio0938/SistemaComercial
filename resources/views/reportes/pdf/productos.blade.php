<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos</title>
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
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .bg-danger { background-color: #dc3545; color: white; }
        .bg-warning { background-color: #ffc107; color: black; }
        .bg-success { background-color: #28a745; color: white; }
    </style>
</head>
<body>
<div class="header">
    <h1>Reporte de Productos</h1>
    <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
    <p>Total de productos: {{ $productos->count() }}</p>
</div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Estado</th>
    </thead>
    </thead>
    <tbody>
    @forelse($productos as $producto)
        <tr>
            <td>{{ $producto->idprod }}</td>
            <td>{{ $producto->nombre }}</td>
            <td>{{ $producto->categoria ?? 'N/A' }}</td>
            <td>${{ number_format($producto->precio, 2) }}</td>
            <td>{{ $producto->stock }}</td>
            <td>
                @if($producto->stock == 0)
                    Agotado
                @elseif($producto->stock < 10)
                    Stock Bajo
                @else
                    Disponible
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" style="text-align: center;">No hay productos registrados</td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="footer">
    <p>Sistema de Gestión Comercial - Reporte generado automáticamente</p>
    <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
</div>
</body>
</html>
