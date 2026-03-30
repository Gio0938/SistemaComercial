<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'idcliente';

    protected $fillable = [
        'nombre', 'rfc', 'email', 'telefono', 'direccion'
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'idcliente');
    }
}
