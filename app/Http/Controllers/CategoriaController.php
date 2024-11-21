<?php

namespace App\Http\Controllers;

use App\DatabaseConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class CategoriaController
{
    public function obtenerCategoriasLista(){
        // Obtener todos las categorias
        $categorias = DatabaseConnection::selectAll('categoria');
        $listaCategorias = json_decode(json_encode($categorias), true);
        return ($listaCategorias);
    }

    public function nuevaCategoria(){
        return view('producto.nuevacategoria');
    }

    public function ingresarNuevaCategoria(Request $request){

        //Validaciones con validador
        $data = $this->verificarCategoria($request);
        if(!$data){
            // Captura los errores y muestra la vista de error
            $message = "Por favor revisa la información ingresada.";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }

        $nombre = $data['nombre'];

        try{

            // Verificar si la categoría ya existe
            $categoriaExistente = DB::table('categoria')->where('nombre', $nombre)->first();

            if ($categoriaExistente) {
                $message = "La categoría con el nombre '{$nombre}' ya existe.";
                $url = url()->previous();
                return view('error', compact('message', 'url'));
            }

            // Llamar a insert para agregar un producto a la tabla
            $inserted = DatabaseConnection::insert('categoria', $data);

            $message = 'Categoría agregada exitosamente.';
            $url =  route('welcome');
            return view('success',compact('message', 'url'));
            
        } catch (QueryException $e){
            $message = "Error de Base datos: ".$e;
            $url = url()->previous();
            return view('error', compact('message', 'url')); 
        }

    }

    public function verificarCategoria(Request $request){
        // validador formulario
        $datos = $request->all();
        $validator = Validator::make($datos, [  
            'nombre' => 'required|string|max:100',
        ]);

    
        if ($validator->fails()) {
            return False;
        }
        $data = $validator->validated();
        return $data;

        
    }
}
