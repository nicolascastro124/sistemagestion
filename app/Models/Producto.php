<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos'; // Nombre de la tabla en la base de datos

    // relacionar id Categoria con producto
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria', 'id');
    }
}
