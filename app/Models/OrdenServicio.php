<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenServicio extends Model
{
    protected $table = 'ordenes_servicio';
    protected $primaryKey = 'idorden';

    protected $fillable = [
        'folio', 'fecha', 'tecnico_nombre', 'tecnico_email', 'tecnico_telefono',
        'departamento', 'cliente_nombre', 'cliente_rfc', 'cliente_email',
        'cliente_telefono', 'equipo_tipo', 'equipo_marca', 'equipo_modelo',
        'equipo_serie', 'especificaciones', 'diagnostico', 'estado', 'total'
    ];

    protected $casts = [
        'fecha' => 'date',
        'total' => 'decimal:2'
    ];

    public function detalles()
    {
        return $this->hasMany(OrdenServicioDetalle::class, 'idorden');
    }

    public function getBadgeEstadoAttribute()
    {
        $badges = [
            'Pendiente' => 'badge bg-warning',
            'En Proceso' => 'badge bg-info',
            'Completado' => 'badge bg-success',
            'Entregado' => 'badge bg-primary'
        ];
        return $badges[$this->estado] ?? 'badge bg-secondary';
    }
}
