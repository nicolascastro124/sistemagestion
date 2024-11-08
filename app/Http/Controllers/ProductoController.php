<?php

namespace App\Http\Controllers;

use App\DatabaseConnection;
use App\Http\Controllers\CategoriaController;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

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
        $categorias = DatabaseConnection::selectAll('categoria');
        $listaCategorias = json_decode(json_encode($categorias), true);
        
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
        $categorias = DatabaseConnection::selectAll('categoria');
        $listaCategorias = json_decode(json_encode($categorias), true);
        $productos = $this->obtenerProductosLista();
        return view('producto.listaproductos', compact('productos', 'categorias'));
    }
    /************************************** */
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
        $data = $this->verificarProducto($request);
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
        $categorias = DatabaseConnection::selectAll('categoria');
        $listaCategorias = json_decode(json_encode($categorias), true);
        return view('producto.modificar', ['producto' => $producto, 'categorias' => $listaCategorias]);
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
        $tabla = 'producto';
        $data = ['activo' => 0];
        $condicion = ['id' => $id];
        $deleted = DatabaseConnection::update($tabla, $data, $condicion);

        $productos = $this->obtenerProductosLista();
        try{
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
}
