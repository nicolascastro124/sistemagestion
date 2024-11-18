<?php

namespace App\Http\Controllers;

use App\DatabaseConnection;
use App\Models\MetodoPago;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;

class MetodoPagoController
{

    public function obtenerMetodosPagoLista(){
        // Obtener todos los clientes
        $metodopago = DatabaseConnection::selectAll('metodopago');
        $listaMetodos = json_decode(json_encode($metodopago), true);
        return ($listaMetodos);

    }
    public function insertarMetodoPago(Request $request){
        $metodopago = $this->obtenerMetodosPagoLista();

        //Validaciones con validador
        $data = $this->verificarMetodoPago($request);
        if(!$data){
            // Captura los errores y muestra la vista de error
            $message = "Por favor revisa la información ingresada.";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }
        // Validaciones manuales
        $descripcion = $data['des'];

        // verificar si existe por nombre
        $descripcionMetodo = array_column($descripcion, 'descripcion');
        $descripcionMetodo = array_map('strtolower', $descripcionMetodo);

        $tabla = 'metodopago';
        $condicion = ['descripcion' => $descripcion];
        $resultado = DatabaseConnection::selectOneStr($tabla, $condicion);
        if (isset($resultado)){
            $message = "Metodo de Pago ya existe";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }
        /**********************************/

        $data = [
            'descripcion' => $data['des'],
        ];
        // Llamar a insert para agregar un producto a la tabla
        $inserted = DatabaseConnection::insert('metodopago', $data);
        if ($inserted) {
            $message = "Metodo de Pago agregado con éxito.";
            $url = url()->previous();
            return view('success',compact('message', 'url'));
        } else {
            $message = "Error al agregar Metodo de Pago.";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }

    }

    //Verificar con validator
    public function verificarMetodoPago(Request $request){
        // validador formulario
        $datos = $request->all();
        $validator = Validator::make($datos, [  
            'descripcion' => 'required|string|max:50',
        ]);

    
        if ($validator->fails()) {
            return False;
        }
        $data = $validator->validated();
        return $data;
        
    }


}
