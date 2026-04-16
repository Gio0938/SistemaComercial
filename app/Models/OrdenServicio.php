<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenServicio extends Model
{
    protected $table = 'ordenes_servicio';
    protected $primaryKey = 'idorden';
    public $timestamps = true;

    protected $fillable = [
        'folio',
        'fecha',
        'tecnico_nombre',
        'tecnico_email',
        'cliente_nombre',
        'cliente_rfc',
        'cliente_email',
        'cliente_telefono',
        'equipo_tipo',
        'equipo_marca',
        'equipo_modelo',
        'equipo_serie',
        'especificaciones',
        'diagnostico',
        'estado',
        'total',
    ];

    protected $casts = [
        'fecha'  => 'datetime',
        'total'  => 'decimal:2',
    ];

    /**
     * Detalles (servicios) de la orden.
     * FK: ordenes_servicio_detalles.idorden → ordenes_servicio.idorden
     */
    public function detalles()
    {
        return $this->hasMany(OrdenServicioDetalle::class, 'idorden', 'idorden');
    }
}
