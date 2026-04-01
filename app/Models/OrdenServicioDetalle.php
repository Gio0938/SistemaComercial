<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenServicioDetalle extends Model
{
    protected $table = 'ordenes_servicio_detalles';
    protected $primaryKey = 'iddetalle';
    public $timestamps = false;

    protected $fillable = [
        'idorden', 'tipo', 'servicio_nombre', 'costo_hr', 'horas',
        'refaccion_nombre', 'costo_refaccion', 'subtotal'
    ];

    protected $casts = [
        'costo_hr' => 'decimal:2',
        'horas' => 'decimal:2',
        'costo_refaccion' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function orden()
    {
        return $this->belongsTo(OrdenServicio::class, 'idorden');
    }
}
