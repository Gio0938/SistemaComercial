<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table = 'modelos';
    protected $primaryKey = 'idmodelo';

    protected $fillable = [
        'nombre',
        'idmarca',
        'tipo_equipo'
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'idmarca');
    }
}
