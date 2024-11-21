<?php

namespace App\Http\Controllers;

use App\DatabaseConnection;
use App\Http\Controllers\CategoriaController;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class ProductoController
{
    protected $categoriaController ;

    public function __construct()
    {
        // Inicializar CategoriaController en el constructor
        $this->categoriaController = new CategoriaController();
    }
    public function obtenerProductosLista(){
        // Obtener todos los productos
        $condicion = ['activo' => 1];
        $productos = DatabaseConnection::selectWithConditions('producto',$condicion);
        $listaProductos = json_decode(json_encode($productos), true); 
        // Obtener todas las categorías
        $listaCategorias = $this->categoriaController->obtenerCategoriasLista();

        $categoriasMap = [];
        foreach ($listaCategorias as $categoria) {
            $categoriasMap[$categoria['id']] = $categoria['nombre'];
        }

        // Añadir el nombre de la categoría a cada producto
        foreach ($listaProductos as &$producto) {
            $producto['nombreCategoria'] = $categoriasMap[$producto['idCategoria']] ?? 'Sin Categoría';
        }
        return $listaProductos;
    }

    public function obtenerProductos(){
        // Obtener todos los productos
        $categorias = $this->categoriaController->obtenerCategoriasLista();
        $productos = $this->obtenerProductosLista();
        return view('producto.listaproductos', compact('productos', 'categorias'));
    }
    
    //Vista Nuevo Producto
    public function nuevoProducto()
    {
        // Obtener todas las categorías
        $categorias = $this->categoriaController->obtenerCategoriasLista();
        return view('producto.registrar', ['categorias' => $categorias]);
    }

    //Insertar Producto
    public function insertarProducto(Request $request)
    {
        $categorias = $this->categoriaController->obtenerCategoriasLista();
        $productos = $this->obtenerProductosLista();
        
        //Validaciones con validador
        $data = $this->verificarInsertarProducto($request);
        if(!$data){
            // Captura los errores y muestra la vista de error
            $message = "Por favor revisa la información ingresada.";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }
        
        // Validaciones manuales
        // Asignar los valores a variables
        $nombre = $data['nombre'];
        $categoria = $data['categoria'];
        $costo = $data['costo'];
        $precioventa = $data['precioventa'];
        $stock = $data['stock'];
        $fechavenc = $data['fechavenc'];

        if ($costo <= 0 || $precioventa <= 0 || $stock < 0 ){
            // Captura los errores y muestra la vista de error
            $message = "Por favor revisa la información ingresada.";
            $url = url()->previous();

            return view('error', compact('message', 'url'));
        }

        // verificar si existe por nombre
        $nombresproductos = array_column($productos, 'nombre');
        $nombresproductos = array_map('strtolower', $nombresproductos);
        $idcategorias = array_column($productos,'idCategoria');


        //Si existe y esta deshabilitado activar nuevamente
        if(in_array(strtolower($nombre), $nombresproductos)){
            if(in_array($categoria,$idcategorias)){

                $tabla = 'producto';
                $data = ['activo' => 1];
                $condicion = ['nombre' => $nombre, 'idCategoria' => $categoria, 'activo' => 0];

                try{
                    $resultado = DatabaseConnection::update($tabla, $data, $condicion);
                    if ($resultado) {
                        $message = "Producto se encontraba inactivo (Se ha vuelto a activar)";
                        session(['productos' => $productos]);
                        $url =  route('producto.listaproductos');
                        return view('success',compact('message', 'url'));
                    } else {
                        $message = "Producto con Nombre y Categoria igual ya existe";
                        $url = url()->previous();
                        return view('error', compact('message', 'url'));
                    }
                } catch (QueryException $e){
                    $message = "Error de Base datos: ".$e;
                    $url = url()->previous();
                    return view('error', compact('message', 'url'));
                }

            }
        }
        /**********************************/
        $data = [
            'nombre' => $data['nombre'],
            'costo' => $data['costo'],
            'precioVenta' => $data['precioventa'],
            'stock' => $data['stock'],
            'fechaVencimiento' => $data['fechavenc'],
            'IdCategoria' => $data['categoria'],
        ];


        // Llamar a insert para agregar un producto a la tabla
        $inserted = DatabaseConnection::insert('producto', $data);

        if ($inserted) {

            $message = "Producto agregado con éxito.";
            session(['productos' => $productos]);
            $url =  route('producto.listaproductos');
            return view('success',compact('message', 'url'));
        } else {
            $message = "Error al agregar Producto.";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }
    }
    /************************************** */

    //Modificar
    //Funcion para vista
    public function editarProductoVista($id)
    {
        // Obtener el producto específico por su ID
        $producto = DatabaseConnection::selectOne('producto', ['id' => $id]);
        $producto = json_decode(json_encode($producto), true); 
        // Obtener todas las categorías para el desplegable
        $categorias = $this->categoriaController->obtenerCategoriasLista();

        return view('producto.modificar', ['producto' => $producto, 'categorias' => $categorias]);
    }

    public function modificarProducto(Request $request){
        $categorias = $this->categoriaController->obtenerCategoriasLista();
        $productos = $this->obtenerProductosLista();

        //Validaciones con validador
        $data = $this->verificarProducto($request);
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
        $categoria = $data['idCategoria'];
        $costo = $data['costo'];
        $precioventa = $data['precioVenta'];
        $stock = $data['stock'];
        $fechavenc = $data['fechaVencimiento'];

        if ($costo <= 0 || $precioventa <= 0 || $stock < 0 ){
            // Captura los errores y muestra la vista de error
            $message = "Por favor revisa la información ingresada.";
            $url = url()->previous();

            return view('error', compact('message', 'url'));
        }

        $data = [
            'id' => $data['id'],
            'nombre' => $data['nombre'],
            'costo' => $data['costo'],
            'precioVenta' => $data['precioVenta'],
            'stock' => $data['stock'],
            'fechaVencimiento' => $data['fechaVencimiento'],
            'IdCategoria' => $data['idCategoria'],
        ];
        // /**********************************/
        //Preparar Valores para manda
        $tabla = 'producto';
        $condiciones = ['id' => $id];
        
        //Obtener producto actual
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
            $message = "No existen cambios en el producto";
            $url = url()->previous();
            return view('error', compact('message', 'url'));
        }

        // Si hay cambios
        try{
            $resultado = DatabaseConnection::update($tabla, $data, $condiciones);
            if ($resultado) {
                $message = "Producto Actualizado con éxito.";
                session(['productos' => $productos]);
                $url =  route('producto.listaproductos');
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

    //Eliminar
    public function eliminarProductoPorId($id)
    {
        if (!is_numeric($id)) {
            abort(404); // Lanza un error 404 si el ID no es numérico
        }
        // eliminar producto por su ID
        try{      
            $tabla = 'producto';
            $data = ['activo' => 0];
            $condicion = ['id' => $id];
            $deleted = DatabaseConnection::update($tabla, $data, $condicion);
    
            $productos = $this->obtenerProductosLista();
            if ($deleted) {
                $message = "Producto Eliminado con éxito.";
                session(['productos' => $productos]);
                $url =  route('producto.listaproductos');
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

    //Verificar con validator
    public function verificarProducto(Request $request){
        // validador formulario
        $datos = $request->all();
        $validator = Validator::make($datos, [  
            'id' => 'required|numeric',
            'nombre' => 'required|string|max:100',
            'costo' => 'required|numeric|min:1',
            'precioVenta' => 'required|numeric|min:1',
            'stock' => 'required|numeric|min:0',
            'fechaVencimiento' => 'required|date|date_format:Y-m-d',
            'idCategoria' => 'required|numeric',
        ]);

    
        if ($validator->fails()) {
            return False;
        }
        $data = $validator->validated();
        return $data;
    
    }

    //Verificar con validator
    public function verificarInsertarProducto(Request $request){
        // validador formulario
        $datos = $request->all();
        $validator = Validator::make($datos, [  
            'nombre' => 'required|string|max:100',
            'costo' => 'required|numeric|min:1',
            'precioventa' => 'required|numeric|min:1',
            'stock' => 'required|numeric|min:0',
            'fechavenc' => 'required|date|date_format:Y-m-d',
            'categoria' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return False;
        }
        $data = $validator->validated();
        return $data;

    }

    //Busca Producto
    public function buscaProductoId($id){
        $producto = DatabaseConnection::selectOne('producto', ['id' => $id]);
        $producto = json_decode(json_encode($producto), true); 
        return $producto;
    }

    //Actualizar stock

    public function actualizaStock($id,$stock,$cantidad){

        try{
            $nuevoStock = $stock - $cantidad;
            if($nuevoStock < 0){
                $message = "Ocurrio un error al actualizar stock";
                $url = url()->previous();
                return view('error', compact('message', 'url'));
            }
            $tabla = 'producto';
            $data = ['stock' => $nuevoStock];
            $condicion = ['id' => $id];
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

    public function productoMasVendido(){

        $sql = "
        SELECT p.nombre AS producto, SUM(dv.cantidad) AS total_vendido FROM detalleventa dv
        JOIN 
            producto p ON dv.idProducto = p.id
        JOIN 
            venta v ON dv.idVenta = v.id
        WHERE v.fecha = ? AND v.activo = 1
        GROUP BY p.nombre ORDER BY total_vendido DESC LIMIT 1;";
        $today = Carbon::today(); //Fecha de hoy
        $fecha = $today->toDateString();
        $result = DatabaseConnection::executeQuery($sql, [$fecha]);

        // Verificar el resultado
        if (!empty($result)) {
            $product = $result[0]; 
            return $product;
        } else {
            return false;
        }
    }
    //Obtener los 5 productos con stock mas bajo
    public function menorStock(){
        $sql = "SELECT nombre AS producto, stock FROM producto ORDER BY stock ASC LIMIT 5;";
        $result = DatabaseConnection::executeQuery($sql, []);

        // Verificar el resultado
        if (!empty($result)) {
            $productos = $result; 
            return $productos;
        } else {
            return false;
        }
        
    }

    public function productoEnRango($inicio,$final){
        $sql = "
        WITH RECURSIVE fechas AS (SELECT ? AS fecha UNION ALL
            SELECT DATE_ADD(fecha, INTERVAL 1 DAY)
            FROM fechas
            WHERE fecha < ?
        ),
        ventas_por_dia AS (
            SELECT f.fecha, p.nombre AS producto, COALESCE(SUM(dv.cantidad), 0) AS total_vendido, ROW_NUMBER() OVER (PARTITION BY f.fecha ORDER BY SUM(dv.cantidad) DESC) AS fila
            FROM fechas f
            LEFT JOIN 
                venta v ON f.fecha = v.fecha
            LEFT JOIN 
                detalleventa dv ON v.id = dv.idVenta
            LEFT JOIN 
                producto p ON dv.idProducto = p.id
            GROUP BY 
                f.fecha, p.nombre
        )
        SELECT fecha, COALESCE(producto, 'No hubo ventas') AS producto, total_vendido
        FROM ventas_por_dia
        WHERE fila = 1
        ORDER BY fecha;";

        $result = DatabaseConnection::executeQuery($sql, [$inicio,$final]);

        // Verificar el resultado
        if (!empty($result)) {
            $productos = $result; 
            return $productos;
        } else {
            return false;
        }

    }

    public function categoriasProductos($inicio,$final){
        $sql = "
        SELECT c.nombre AS categoria, 
        COALESCE(SUM(dv.cantidad), 0) AS cantidad_vendida,
        COALESCE(SUM(dv.cantidad * p.precioVenta), 0) AS total_ventas
        FROM categoria c
        LEFT JOIN 
            producto p ON c.id = p.idCategoria
        LEFT JOIN 
            detalleventa dv ON p.id = dv.idProducto
        LEFT JOIN 
            venta v ON dv.idVenta = v.id
        WHERE 
            v.fecha BETWEEN ? AND ? OR v.fecha IS NULL
        GROUP BY c.id, c.nombre ORDER BY total_ventas DESC;";

        $result = DatabaseConnection::executeQuery($sql, [$inicio,$final]);

        // Verificar el resultado
        if (!empty($result)) {
            $productos = $result; 
            return $productos;
        } else {
            return false;
        }
    }

    public function rentabilidadProductos($inicio,$final){
        $sql = "
        SELECT p.nombre AS producto,
            SUM(dv.cantidad) AS cantidad_vendida,
            SUM(dv.cantidad * p.costo) AS costo_total,
            SUM(dv.cantidad * p.precioVenta) AS ingreso_total,
            SUM(dv.cantidad * (p.precioVenta - p.costo)) AS ganancia_total,
            ROUND(SUM(dv.cantidad * (p.precioVenta - p.costo)) / NULLIF(SUM(dv.cantidad * p.costo), 0) * 100, 2) AS margen_rentabilidad
        FROM detalleventa dv
        JOIN producto p ON dv.idProducto = p.id
        JOIN venta v ON dv.idVenta = v.id
        WHERE v.fecha BETWEEN ? AND ?
        GROUP BY p.id, p.nombre
        ORDER BY ganancia_total DESC;
        ";
        $result = DatabaseConnection::executeQuery($sql, [$inicio,$final]);

        // Verificar el resultado
        if (!empty($result)) {
            $productos = $result; 
            return $productos;
        } else {
            return false;
        }
    }


}
