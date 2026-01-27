<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';
    protected $primaryKey = 'idserv';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'foto',
        'tipo_servicio',
        'disponible',
        'categoria',
        'duracion',
        'personal_requerido',
        'materiales_incluidos',
        'garantia'
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'materiales_incluidos' => 'boolean',
        'garantia' => 'boolean',
        'precio' => 'decimal:2',
        'duracion' => 'decimal:2'
    ];

    public function promociones()
    {
        return $this->hasMany(Promocion::class, 'servicio_id');
    }

    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_servicio', $tipo);
    }

    public function promocionesActivas()
    {
        return $this->hasMany(Promocion::class, 'servicio_id')
            ->where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now());
    }
}
