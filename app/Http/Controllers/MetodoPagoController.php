<?php

namespace App\Http\Controllers;

use App\DatabaseConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class MetodoPagoController
{
    public function obtenerMetodosPagoLista(){
        // Obtener todos las categorias
        $metodospago = DatabaseConnection::selectAll('metodopago');
        $listaMetodos = json_decode(json_encode($metodospago), true);
        return ($listaMetodos);
    }

    public function nuevoMetodo(){
        return view('venta.nuevometodo');
    }

    public function ingresarNuevoMetodo(Request $request){

        //Validaciones con validador
        $data = $this->verificarMetodo($request);
        if(!$data){
            // Captura los errores y muestra la vista de error
            $message = "Por favor revisa la información ingresada.";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }

        $descripcion = $data['descripcion'];

        try{

            // Verificar si la categoría ya existe
            $categoriaExistente = DB::table('metodopago')->where('descripcion', $descripcion)->first();

            if ($categoriaExistente) {
                $message = "El metodo de pago con el nombre '{$descripcion}' ya existe.";
                $url = url()->previous();
                return view('error', compact('message', 'url'));
            }

            // Llamar a insert para agregar un producto a la tabla
            $inserted = DatabaseConnection::insert('metodopago', $data);

            $message = 'Metodo Pago agregado exitosamente.';
            $url =  route('welcome');
            return view('success',compact('message', 'url'));

        } catch (QueryException $e){
            $message = "Error de Base datos: ".$e;
            $url = url()->previous();
            return view('error', compact('message', 'url')); 
        }

    }

    public function verificarMetodo(Request $request){
        // validador formulario
        $datos = $request->all();
        $validator = Validator::make($datos, [  
            'descripcion' => 'required|string|max:100',
        ]);

    
        if ($validator->fails()) {
            return False;
        }
        $data = $validator->validated();
        return $data;

        
    }
}
