<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marcas';
    protected $primaryKey = 'idmarca';

    protected $fillable = [
        'nombre',
        'tipo_equipo'
    ];

    public function modelos()
    {
        return $this->hasMany(Modelo::class, 'idmarca');
    }

    public function modelosPorTipo($tipo)
    {
        return $this->modelos()->where('tipo_equipo', $tipo)->get();
    }
}
