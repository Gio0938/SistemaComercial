<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'idprod';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'foto',
        'marca',
        'disponible',
        'numero_piezas',
        'stock',
        'categoria',
        'proveedor',
        'peso',
        'dimensiones',
        'codigo_barras'
    ];

    protected $casts = [
        'marca' => 'boolean',
        'disponible' => 'boolean',
        'precio' => 'decimal:2',
        'peso' => 'decimal:2'
    ];

    public function promociones()
    {
        return $this->hasMany(Promocion::class, 'producto_id');
    }

    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }

    public function scopeMarcaPropia($query)
    {
        return $query->where('marca', true);
    }

    public function scopeStockBajo($query, $limite = 5)
    {
        return $query->where('stock', '<=', $limite);
    }

    public function promocionesActivas()
    {
        return $this->hasMany(Promocion::class, 'producto_id')
            ->where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now());
    }
}
