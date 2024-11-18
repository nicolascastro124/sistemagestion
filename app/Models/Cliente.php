<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente'; // Nombre de la tabla en la base de datos

    // Relación con venta
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'idCliente', 'id');
    }
    
}
