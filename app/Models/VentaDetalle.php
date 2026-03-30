<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    protected $table = 'ventas_detalles';
    protected $primaryKey = 'iddetalle';
    public $timestamps = false;

    protected $fillable = [
        'idventa',
        'item_type',
        'item_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'garantia',
        'duracion_garantia',
        'especificaciones'
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'garantia' => 'boolean'
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'idventa');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'item_id', 'idprod');
    }
}
