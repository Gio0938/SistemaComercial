<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Promociones</title>
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
    <h1>Reporte de Promociones</h1>
    <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
</div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Tipo</th>
        <th>Descuento</th>
        <th>Fecha Inicio</th>
        <th>Fecha Fin</th>
        <th>Estado</th>
    </thead>
    </thead>
    <tbody>
    @forelse($promociones as $promocion)
        <tr>
            <td>{{ $promocion->idpromo }}</td>
            <td>{{ $promocion->nombre }}</td>
            <td>{{ $promocion->tipo_promocion }}</td>
            <td>
                @if($promocion->tipo_promocion == 'Porcentaje')
                    {{ $promocion->descuento }}%
                @elseif($promocion->tipo_promocion == 'Fijo')
                    ${{ number_format($promocion->descuento, 2) }}
                @else
                    {{ $promocion->tipo_promocion }}
                @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($promocion->fecha_inicio)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y') }}</td>
            <td>
                @if($promocion->activa && $promocion->fecha_fin >= now())
                    Activa
                @elseif($promocion->fecha_inicio > now())
                    Próxima
                @else
                    Expirada
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" style="text-align: center;">No hay promociones registradas</td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="footer">
    <p>Sistema de Gestión Comercial</p>
</div>
</body>
</html>
