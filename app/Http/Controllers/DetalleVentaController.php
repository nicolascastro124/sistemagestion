<?php

namespace App\Http\Controllers;

use App\DatabaseConnection;
use App\Http\Controllers\ProductoController;

use Illuminate\Http\Request; 
use Illuminate\Database\QueryException;

class DetalleVentaController
{

    protected $productoController;


    public function __construct()
    {
        $this->productoController = new ProductoController();
    }


    public function obtenerDetallesVentaLista(){
        // Obtener todos los clientes
        $detalles = DatabaseConnection::selectAll('detalleVenta');
        $listaDetalleVentas = json_decode(json_encode($detalles), true);
        return ($listaDetalleVentas);

    }

    public function obtenerDetallePorId($id){
        //Obtener producto actual
        $condicion = ['idVenta' => $id];

        $detalle = DatabaseConnection::selectWithConditions('detalleVenta', $condicion);
        $listaDetalleVentas = json_decode(json_encode($detalle), true);
        // var_dump($detalle);

        //Traducir Nombres Productos
        $listaProducto = $this->productoController->obtenerProductosLista();

        $productosMap = [];
        foreach ($listaProducto as $producto) {
            $productosMap[$producto['id']] = $producto['nombre'];
        }
        // Añadir descripcion de metodo pago
        foreach ($listaDetalleVentas as &$detalle) {
            $detalle['nombreProducto'] = $productosMap[$detalle['idProducto']] ?? 'Sin Descripcion';
        }
        
        return $listaDetalleVentas;
    }

    public function insertarDetalle($data){
        $inserted = DatabaseConnection::insert('detalleVenta', $data);

        if ($inserted) {
            return true;
        } else {
            return false;
        }
    }

    //Busca Detalles Para Venta
    public function buscaDetallesVentaId($id){
        $detalles = DatabaseConnection::selectWithConditions('detalleVenta', ['idVenta' => $id]);
        $detalles = json_decode(json_encode($detalles), true); 
        return $detalles;
    }

    public function actualizaDetalleVentaId($data,$idDetalle){
        $tabla = 'detalleVenta';
        $condicion = ['id' => $idDetalle];
        try{
            $resultado = DatabaseConnection::update($tabla, $data, $condicion);
            if ($resultado) {
                return true;
            } else {
                return false;
            }
        } catch (QueryException $e){
            $message = "Error de Base datos ".$e;
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }
    }
    




}
