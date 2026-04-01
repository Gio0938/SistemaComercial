<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Servicios</title>
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
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Reporte de Servicios</h1>
    <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
</div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Tipo</th>
        <th>Precio</th>
        <th>Duración</th>
        <th>Disponible</th>
    </thead>
    </thead>
    <tbody>
    @forelse($servicios as $servicio)
        <tr>
            <td>{{ $servicio->idserv }}</td>
            <td>{{ $servicio->nombre }}</td>
            <td>{{ $servicio->tipo_servicio }}</td>
            <td>${{ number_format($servicio->precio, 2) }}</td>
            <td>{{ $servicio->duracion ? $servicio->duracion . ' hrs' : 'N/A' }}</td>
            <td>{{ $servicio->disponible ? 'Sí' : 'No' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="6" style="text-align: center;">No hay servicios registrados</td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="footer">
    <p>Sistema de Gestión Comercial</p>
</div>
</body>
</html>
