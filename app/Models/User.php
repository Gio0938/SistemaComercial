<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Especificar el nombre de la tabla
    protected $table = 'usuarios';

    // Campos que se pueden llenar
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    // Campos ocultos
    protected $hidden = [
        'password',
        'remember_token',
    ];

   /* public function esAdmin()
    {
        return $this->rol === 'admin';
    }*/

    public function esAdmin()
    {
        return $this->rol === 'admin';
    }

}
