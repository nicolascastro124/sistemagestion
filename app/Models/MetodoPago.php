<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = 'metodopago'; // Nombre de la tabla en la base de datos

    // Relación con venta
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'idMetodoPago', 'id');
    }
    
}
