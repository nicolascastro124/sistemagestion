<?php

namespace App\Http\Controllers;

use App\DatabaseConnection;
use App\Http\Controllers\MetodoPagoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\ProductoController;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentaController
{

    protected $metodoPagoController;
    protected $clienteController;
    protected $detalleVentaController;
    protected $productoController;

    public function __construct()
    {
        $this->metodoPagoController = new MetodoPagoController();
        $this->clienteController = new ClienteController();
        $this->detalleVentaController = new DetalleVentaController();
        $this->productoController = new ProductoController();


    }

    public function obtenerVentasLista(){
        // Obtener todas las Ventas
        $condicion = ['activo' => 1];
        $ventas = DatabaseConnection::selectWithConditions('venta',$condicion);
        $ventas = $ventas->sortByDesc('fecha');

        $listaVentas = json_decode(json_encode($ventas), true);

        /********/
        //Obtener metodos de pago
        $listaMetodos = $this->metodoPagoController->obtenerMetodosPagoLista();

        $metodosMap = [];
        foreach ($listaMetodos as $metodo) {
            $metodosMap[$metodo['id']] = $metodo['descripcion'];
        }
        // Añadir descripcion de metodo pago
        foreach ($listaVentas as &$venta) {
            $venta['nombreMetodoPago'] = $metodosMap[$venta['idMetodoPago']] ?? 'Sin Descripcion';
        }
        
        /********/
        //Obtener Clientes
        $listaClientes = $this->clienteController->obtenerClientesLista();
        $clientesMap = [];
        foreach ($listaClientes as $cliente) {
            $clientesMap[$cliente['rut']] = $cliente['nombre'];
        }
        // Añadir el nombre de cliente a las ventas
        foreach ($listaVentas as &$venta) {
            $venta['nombreCliente'] = $clientesMap[$venta['rutCliente']] ?? 'Sin Descripcion';
        }
        
        return ($listaVentas);

    }

    public function obtenerVentas(){
        $ventas = $this->obtenerVentasLista();
        return view('venta.listaventas', compact('ventas'));

    }

    public function obtenerDetalle($id){
        $detalles = $this->detalleVentaController->obtenerDetallePorId($id);
        //Traduccion Nombres

        return $detalles;
    } 

    //Vista Nueva Venta
    public function nuevaVenta()
    {
        // Obtener todas los productos
        date_default_timezone_set('America/Santiago'); 

        $horaActual = date('H:i');
        $productos = $this->productoController->obtenerProductosLista();
        $metodospago = $this->metodoPagoController->obtenerMetodosPagoLista();
        $clientes = $this->clienteController->obtenerClientesLista();
        return view('venta.registrar',[
            'metodospago' => $metodospago,
            'productos' => $productos,
            'clientes' => $clientes,
            'horaActual' => $horaActual,
        ]);
    }

    //Agregar nueva venta
    public function insertaVenta(Request $request){
        //verificación con validator
        $data = $this->verificarVenta($request);
        if (!$data) {
            $message = "Por favor revisa la información ingresada.";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
            // return redirect()->back()->with('error', 'Error en la validación de los datos.');
        }

        //Validaciones manuales
        $fechaVenta = $data['fechaVenta'];
        $horaVenta = $data['hora'];
        $metodoPago = $data['metodoPago'];
        $clienteRut = $data['clienteRut'];
        $detalles = $data['detalles'];
        
        
        //Calcular total
        $totalVenta = 0;
        foreach ($detalles as $detalle){
            $codigoProducto = $detalle['codigo_producto'];
            $cantidad = $detalle['cantidad'];  
            $productoObtenido = $this->productoController->buscaProductoId($codigoProducto);
            $precioVenta = $productoObtenido['precioVenta'];
            $stock = $productoObtenido['stock'];

            if ($cantidad < $stock){
                $totalVenta += ($precioVenta)*($cantidad);
            }else{
                $message = "Error en Stock de producto ".$productoObtenido['nombre'];
                $url = url()->previous();
                return view('error', compact('message', 'url'));
            }

        }
        //Comienzo proceso BD
        DB::beginTransaction();
        try{

            $data = [
                'fecha' => $fechaVenta,
                'hora'=> $horaVenta,
                'totalVenta' => $totalVenta,
                'idMetodoPago' => $metodoPago,
                'rutCliente' => $clienteRut,
            ];
            //Insertar  ventas
            $idInserted = DatabaseConnection::insertGetId('venta', $data);

            //Insertar Detalles
            foreach ($detalles as $detalle){
                $codigoProducto = $detalle['codigo_producto'];
                $productoObtenido = $this->productoController->buscaProductoId($codigoProducto);
                $precioVenta = $productoObtenido['precioVenta'];
                $cantidad = $detalle['cantidad'];
                $subtotal = ($precioVenta)*($cantidad);
                $stock = $productoObtenido['stock'];
                $data = [
                    'idVenta' => $idInserted,
                    'idProducto' => $detalle['codigo_producto'],
                    'cantidad' => $detalle['cantidad'],
                    'subtotal' => $subtotal,
                ];
                    
                $inserted = $this->detalleVentaController->insertarDetalle($data);
                
                $update = $this->productoController->actualizaStock($codigoProducto,$stock,$cantidad);

            }

            DB::commit();
            $message = "Venta agregada con éxito.";
            $url =  route('venta.lista');
            return view('success',compact('message', 'url'));

        } catch (QueryException $e){
            DB::rollBack();
            $message = "Error de Base datos: ".$e;
            $url = url()->previous();
            return view('error', compact('message', 'url')); 
        }

    }

    //Verificar con validator
    public function verificarVenta(Request $request){
        // validador formulario
        $datos = [
            'fechaVenta' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'metodoPago' => 'required|string',
            'clienteRut' => 'required|numeric',
            'detalles' => 'required|array',
            'detalles.*.codigo_producto' => 'required|string',
            'detalles.*.cantidad' => 'required|numeric',
        ];
        // Ejecuta la validación
        $validator = Validator::make($request->all(), $datos);
    
        if ($validator->fails()) {
            return false;
        }
        
        $data = $validator->validated();
        return $data;
    }

    //Eliminacion Venta
    public function eliminarVentaPorId($id){
        if (!is_numeric($id)) {
            abort(404); // Lanza un error 404 si el ID no es numérico
        }
        // eliminar producto por su ID

        try{
            $tabla = 'venta';
            $data = ['activo' => 0];
            $condicion = ['id' => $id];
            $deleted = DatabaseConnection::update($tabla, $data, $condicion);
            if ($deleted) {
                $message = "Venta Eliminada con éxito.";
                $url =  route('venta.lista');
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

    //Modificacion venta vista
    public function editarVentaVista($id){
        $venta = $this->buscaVentaId($id);
        $detalles = $this->detalleVentaController->buscaDetallesVentaId($id);
        $productos = $this->productoController->obtenerProductosLista();
        $metodospago = $this->metodoPagoController->obtenerMetodosPagoLista();
        $clientes = $this->clienteController->obtenerClientesLista();
        $venta['hora'] = $hora = Carbon::parse($venta['hora'])->format('H:i');

        return view('venta.modificar', compact('venta', 'detalles','productos','metodospago','clientes')); 
    }

    //Busca Venta
    public function buscaVentaId($id){
        $venta = DatabaseConnection::selectOne('venta', ['id' => $id]);
        $venta = json_decode(json_encode($venta), true); 
        return $venta;
    }

    public function modificarVenta(Request $request){
        //verificación con validator
        $data = $this->validarModificar($request);
        if (!$data) {
            $message = "Por favor revisa la información ingresada.";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
            return redirect()->back()->with('error', 'Error en la validación de los datos.');
        }
        //Validaciones manuales
        $idVenta = $data['id'];
        $fechaVenta = $data['fechaVenta'];
        $horaVenta = $data['hora'];
        $metodoPago = $data['metodoPago'];
        $clienteRut = $data['clienteRut'];
        $detalles = $data['detalles'];
        $totalVenta = 0;
        //Proceso Bd
        try{
            //Obtener datos de detalles venta
            foreach ($detalles as $detalle){
                $idDetalle = $detalle['id'];
                $cant = isset($detalle['cant']) ? $detalle['cant'] : 0; //Cantidad anteriormente ingresada
                $codigoProducto = $detalle['codigo_producto'];
                $cantidad = $detalle['cantidad'];
                $productoObtenido = $this->productoController->buscaProductoId($codigoProducto);
                $precioVenta = $detalle['precioUnitario'];

                //Calcular stock
                $stock = $productoObtenido['stock'];
                $cantidadDetalle = $cantidad - $cant;
                $resultadoStock = $stock - $cantidadDetalle;
                if($resultadoStock < 0){
                    $message = "Error en Stock de producto ".$productoObtenido['nombre'];
                    $url = url()->previous();
                    return view('error', compact('message', 'url'));
                }
                //calcular totales
                $subtotal = ($precioVenta) * ($cantidad);
                $totalVenta = $totalVenta + $subtotal;
                //Preparacion para actualizar detalle
                $data = [
                    'idProducto' => $codigoProducto,
                    'cantidad'=> $cantidad,
                    'subtotal' => $subtotal,
                ];
                $condicion = ['id' => $idDetalle];
                $update = $this->detalleVentaController->actualizaDetalleVentaId($data,$idDetalle);
                $update = $this->productoController->actualizaStock($codigoProducto,$stock,$cantidadDetalle);

            }

            // Actualizacion Venta
            $data = [
                'fecha' => $fechaVenta,
                'hora'=> $horaVenta,
                'totalVenta' => $totalVenta,
                'idMetodoPago' => $metodoPago,
                'rutCliente' => $clienteRut,
            ];
            $tabla = 'venta';
            $condicion = ['id' => $idVenta];
            $resultado = DatabaseConnection::update($tabla, $data, $condicion);
            //Verificacion resultado
            if ($resultado) {
                $message = "Venta Actualizada con éxito.";
                $url =  route('venta.lista');
                return view('success',compact('message', 'url'));
            } else {
                $message = "No existen cambios en la Venta";
                $url = url()->previous();
                return view('error', compact('message', 'url'));
            }
            

        }catch (QueryException $e){
            $message = "Error de Base datos: ".$e;
            $url = url()->previous();
            return view('error', compact('message', 'url')); 
        }

    }

    //Verificar con validator
    public function validarModificar(Request $request){
        $datos = [
            'id' => 'required|numeric',
            'fechaVenta' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'metodoPago' => 'required|string',
            'clienteRut' => 'required|numeric',
            'detalles' => 'required|array',
            'detalles.*.codigo_producto' => 'numeric',
            'detalles.*.cantidad' => 'numeric',
            'detalles.*.precioUnitario' => 'numeric',
            'detalles.*.cant' => 'numeric',
            'detalles.*.id' => 'string',
        ];
        // Ejecuta la validación
        $validator = Validator::make($request->all(), $datos);
    
        if ($validator->fails()) {
            dd($validator->errors()->all());
            return false;
        }
        
        $data = $validator->validated();
        return $data;
    }

    public function cantidadVentasHoy(){

        $sql = "
        SELECT 
            COUNT(*) AS cantidad_ventas_hoy,
            SUM(totalVenta) AS total_ventas_hoy
        FROM 
            venta
        WHERE 
            fecha = ? AND activo = 1;
        ";
        $today = Carbon::today(); //Fecha de hoy
        $fecha = $today->toDateString();
        $result = DatabaseConnection::executeQuery($sql, [$fecha]);

        // Verificar el resultado
        if (!empty($result)) {
            $venta = $result[0]; 
            return $venta;
        } else {
            return false;
        }
    }

    public function ventasPorDia($inicio,$final){
        $sql = "
        WITH RECURSIVE fechas AS (
            SELECT ? AS fecha
            UNION ALL
            SELECT DATE_ADD(fecha, INTERVAL 1 DAY)
            FROM fechas
            WHERE fecha < ?
        )
        SELECT f.fecha, COALESCE(SUM(v.totalVenta), 0) AS total_ventas_diarias, COALESCE(COUNT(v.id), 0) AS cantidad_ventas_diarias
        FROM 
            fechas f
        LEFT JOIN 
            venta v ON f.fecha = v.fecha AND v.activo = 1
        GROUP BY 
            f.fecha
        ORDER BY 
            f.fecha;
        ";

        $result = DatabaseConnection::executeQuery($sql, [$inicio,$final]);

        // Verificar el resultado
        if (!empty($result)) {
            $ventas = $result; 
            return $ventas;
        } else {
            return false;
        }
    }
}

