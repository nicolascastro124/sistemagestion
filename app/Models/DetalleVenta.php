<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}

?>