<?php
// app/Models/Promocion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';
    protected $primaryKey = 'idpromo';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_promocion',
        'descuento',
        'precio_promocional',
        'fecha_inicio',
        'fecha_fin',
        'activa',
        'limite_usos',
        'usos_actuales',
        'codigo_promocion',
        'aplica_todos_servicios',
        'aplica_todos_productos',
        'condiciones',
        'servicio_id',
        'producto_id'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'aplica_todos_servicios' => 'boolean',
        'aplica_todos_productos' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'descuento' => 'decimal:2',
        'precio_promocional' => 'decimal:2'
    ];

    // Relaciones
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id', 'idserv');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'idprod');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now());
    }

    public function scopeVigentes($query)
    {
        return $query->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now());
    }

    public function scopeProximas($query)
    {
        return $query->where('fecha_inicio', '>', now());
    }

    public function scopeExpiradas($query)
    {
        return $query->where('fecha_fin', '<', now());
    }

    // Métodos de ayuda
    public function getEstadoAttribute()
    {
        $hoy = now();

        if (!$this->activa) {
            return 'inactiva';
        }

        if ($hoy->lt($this->fecha_inicio)) {
            return 'programada';
        }

        if ($hoy->gt($this->fecha_fin)) {
            return 'expirada';
        }

        return 'activa';
    }

    public function getDiasRestantesAttribute()
    {
        return now()->diffInDays($this->fecha_fin, false);
    }

    public function getEsValidaAttribute()
    {
        return $this->estado === 'activa' &&
            ($this->limite_usos === null || $this->usos_actuales < $this->limite_usos);
    }

    public function incrementarUso()
    {
        if ($this->limite_usos !== null) {
            $this->increment('usos_actuales');
        }
    }
}
