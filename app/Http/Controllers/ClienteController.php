<?php

namespace App\Http\Controllers;

use App\DatabaseConnection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ClienteController
{

    public function obtenerClientesLista(){
        // Obtener todos los clientes
        $condicion = ['activo' => 1];
        $clientes = DatabaseConnection::selectWithConditions('cliente',$condicion);
        $listaClientes = json_decode(json_encode($clientes), true);
        return $listaClientes;
    }

    public function obtenerClientes(){
        $clientes = $this->obtenerClientesLista();
        return view('cliente.listaclientes', compact('clientes'));
    }

    /************************************** */
    //Vista Nuevo Cliente
    public function nuevoCliente(){
        return view('cliente.registrar');
    }

    public function insertarCliente(Request $request){
        $clientes = $this->obtenerClientesLista();
        //Validaciones con validador
        $data = $this->verificarCliente($request);
        if(!$data){
            // Captura los errores y muestra la vista de error
            $message = "Por favor revisa la información ingresada.";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }  
        // Validaciones manuales
        // Asignar los valores a variables
        $nombre = $data['nombre'];
        $rut = $data['rut'];
        $telefono = $data['telefono'];

        //Buscar inactivos
        $condicion = ['activo' => 0];
        $clientesInactivos = DatabaseConnection::selectWithConditions('cliente',$condicion);
        $listaInactivos = json_decode(json_encode($clientesInactivos), true);
        $rutinactivos = array_column($listaInactivos, 'rut');

        if (in_array($rut,$listaInactivos)){
            $tabla = 'cliente';
            $data = [
                'nombre' => $nombre,
                'rut' => $rut,
                'telefono' => $telefono,
                'activo' => 1
            ];
            $condicion = ['rut' => $rut];
            try{
                $resultado = DatabaseConnection::update($tabla, $data, $condicion);
                if ($resultado) {
                    $message = "Cliente se encontraba inactivo (Se ha vuelto a activar)";
                    session(['clientes' => $clientes]);
                    $url =  route('cliente.listaclientes');
                    return view('success',compact('message', 'url'));
                }
            }catch (QueryException $e){
                $message = "Error de Base datos: ".$e;
                $url = url()->previous();
                return view('error', compact('message', 'url')); 
            }

        }

        //Verificar si existe por rut
        $rutclientes = array_column($clientes, 'rut');
        if (in_array($rut,$rutclientes)){
            $message = "Cliente ya existe";
            session(['clientes' => $clientes]);
            $url =  route('cliente.listaclientes');
            return view('success',compact('message', 'url'));
        }

        //Procedimiento para insert
        $data = [
            'nombre' => $data['nombre'],
            'rut' => $data['rut'],
            'dv' => $this->obtenerDv($rut),
            'telefono' => $data['telefono'],

        ];
        $inserted = DatabaseConnection::insert('cliente', $data);
        if ($inserted) {
            $message = "Cliente agregado con éxito.";
            session(['clientes' => $clientes]);
            $url =  route('cliente.listaclientes');
            return view('success',compact('message', 'url'));
        } else {
            $message = "Error al agregar Cl.";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }
    }

    /************************************* */
    //Modificar
    //Funcion para vista modificar
    public function editarClienteVista ($id){
        // Obtener el cliente específico por su ID
        $cliente = DatabaseConnection::selectOne('cliente', ['id' => $id]);
        $cliente = json_decode(json_encode($cliente), true); 
        return view('cliente.modificar', ['cliente' => $cliente]);
    }

    public function modificarCliente (Request $request){
        $clientes = $this->obtenerClientesLista();
        //Validaciones con validador
        $data = $this->verificarCliente($request);
        if(!$data){
            // Captura los errores y muestra la vista de error
            $message = "Por favor revisa la información ingresada.";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }  
        // Validaciones manuales
        // Asignar los valores a variables
        $id = $data['id'];
        $nombre = $data['nombre'];
        $rut = $data['rut'];
        $telefono = $data['telefono'];

        // /**********************************/
        //Preparar Valores para manda
        $data = [
            'id' => $data['id'],
            'nombre' => $data['nombre'],
            'rut' => $data['rut'],
            'dv' => $this->obtenerDv($rut),
            'telefono' => $data['telefono'],
        ];
        $tabla = 'cliente';
        $condiciones = ['id' => $id];

        //Obtener cliente actual
        $actual = DatabaseConnection::selectOne($tabla, $condiciones);
        $actual = json_decode(json_encode($actual), true);

        // Comparar data con los datos actuales en la base de datos
        $sinCambios = true;
        foreach ($data as $campo => $valorNuevo) {
            if (isset($actual[$campo]) && $actual[$campo] != $valorNuevo) {
                $sinCambios = false;
                break;
            }
        }
        // No hay cambios
        if ($sinCambios) {
            $message = "No existen cambios en el Cliente";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }

        // Si hay cambios
        try{
            $resultado = DatabaseConnection::update($tabla, $data, $condiciones);
            if ($resultado) {
                $message = "Cliente Actualizado con éxito.";
                session(['cliente' => $clientes]);
                $url =  route('cliente.listaclientes');
                return view('success',compact('message', 'url'));
            } else {
                $message = "No existen cambios en el producto";
                $url = url()->previous();
                return view('error', compact('message', 'url'));
            }
        } catch (QueryException $e){
            $message = "Error de Base datos ".$e;
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }

    }
    /************************* */
    //Eliminar
    public function eliminarClientePorId($id){
        if (!is_numeric($id)) {
            abort(404); // Lanza un error 404 si el ID no es numérico
        }
        $tabla = 'cliente';
        $data = ['activo' => 0];
        $condicion = ['id' => $id];
        $deleted = DatabaseConnection::update($tabla, $data, $condicion);
        
        $clientes = $this->obtenerClientesLista();
        try{
            if ($deleted) {
                $message = "Cliente Eliminado con éxito.";
                session(['clientes' => $clientes]);
                $url =  route('cliente.listaclientes');
                return view('success',compact('message', 'url'));
            } else {
                $message = "Ocurrio un error al eliminar";
                $url = url()->previous();
                return view('error', compact('message', 'url'));
            }
        } catch (QueryException $e){
            $message = "Error de Base datos ".$e;
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }

    }

    /************************* */
    //Verificar con validator
    public function verificarCliente(Request $request){
        // validador formulario
        $datos = $request->all();
        $validator = Validator::make($datos, [  
            'id' => 'numeric',
            'nombre' => 'required|string|max:100',
            'rut' => 'required|string|max:15|min:1',
            'telefono' => 'required|string|max:15|min:1',
        ]);
        if ($validator->fails()) {
            return False;
        }
        $data = $validator->validated();
        return $data;

        
    }
    
    public function obtenerDv($rut){
        $rut = strrev($rut); // Invierte el RUT
        $suma = 0;
        $multi = 2;

        for ($i = 0 ;  $i < strlen($rut) ; $i ++){
            $suma += intval($rut[$i]) * $multi; // multiplicar cada numero y sumar
            $multi++;
            //llega a 7 y vuelve a 2
            if($multi > 7){
                $multi = 2;
            }
        }
        //Modulo 11 
        $resto  = $suma % 11;
        $dv = 11 - $resto; // Se resta lo obtenido de modulo 11

        //11 = 0 ; 10 = K
        if($dv == 11){
            return '0';
        }elseif($dv == 10){
            return 'K';
        }else{
            return strval($dv);
        }

        
    }
}

