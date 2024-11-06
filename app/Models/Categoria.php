<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias'; // Nombre de la tabla en la base de datos

    // Relación con productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'idCategoria', 'id');
    }
}
