<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';

    // Relación con metodo de pago
    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'idMetodoPago', 'id');
    }

    // Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'id');
    }
    
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
