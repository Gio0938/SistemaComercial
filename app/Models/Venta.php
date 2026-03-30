<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'idventa';

    protected $fillable = [
        'folio', 'iduser', 'idcliente', 'subtotal', 'iva', 'total',
        'tipo_venta', 'estado'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idcliente');
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'idventa');
    }
}
