<?php

namespace App\Http\Controllers;

use App\DatabaseConnection;
use Illuminate\Http\Request; // Asegúrate de importar la clase Request

class CategoriaController
{
    public function obtenerCategoriasLista(){
        // Obtener todos los clientes
        $categorias = DatabaseConnection::selectAll('categoria');
        $listaCategorias = json_decode(json_encode($categorias), true);
        return ($listaCategorias);

    }

    public function nuevaCategoria(){
        
    }


}
