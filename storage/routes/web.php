<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;

//Ruta creada por laravel
Route::get('/', function () {
    return view('welcome');
});

//Ruta helloworld para pruebas
Route::get('/helloworld', function () {
    return view('helloworld');
});

//Ruta para probar conexion BD
Route::get('/test-connection', function () {
    ob_start();
    require_once base_path('/database/conexion.php'); 
    $output = ob_get_clean();

    return view('test-connection', ['output' => $output]);
});

//ruta para probar error HTTP
Route::get('/error500', function () {
    abort(500);
});


//**************************************************************/
//Rutas para Producto
Route::match(['get', 'post'], '/productos' , [ProductoController::class, 'obtenerProductos'])->name('producto.listaproductos'); //Lista
Route::get('/producto/views/nuevo', [ProductoController::class, 'nuevoProducto'])->name('producto.registrar'); //Lista

Route::post('/producto/agregar', [ProductoController::class, 'insertarProducto'])->name('producto.agregar'); // Listo

Route::get('/producto/editar/{id}', [ProductoController::class, 'editarProductoVista'])->name('producto.editar'); //Listo
Route::put('/producto/actualizar/', [ProductoController::class, 'modificarProducto'])->name('producto.actualizar'); //Listo

Route::delete('/producto/eliminar/{id}', [ProductoController::class, 'eliminarProductoPorId'])  //Listo
->where('id', '[0-9]+')
->name('producto.eliminar');


Route::get('/producto/buscar/id/{id}', [ProductoController::class, 'obtenerProductoPorId']);
Route::get('/producto/buscar/nombre/{Nombre}', [ProductoController::class, 'obtenerProductoPorNombre']);
Route::get('/producto/buscar/categoria/{categoria}', [ProductoController::class, 'obtenerProductoPorCategoria']);


//**************************************************************/


//Rutas para cliente
Route::get('/cliente', [ClienteController::class, 'obtenerClientes']);
Route::get('/cliente/buscar/{rut}', [ClienteController::class, 'obtenerClientePorRut']);
Route::get('/cliente/nuevo', function () {
    return view('cliente.agregarcliente');
});

Route::post('/cliente/agregar', [ClienteController::class, 'insertarCliente']);
Route::delete('/cliente/eliminar/rut', [ClienteController::class, 'eliminarClientePorRut']);
Route::delete('/cliente/eliminar/id', [ClienteController::class, 'eliminarClientePorId']);

Route::get('/cliente/view/eliminar/id', function () {
    return view('cliente.eliminarid');
});

Route::get('/cliente/view/eliminar/rut', function () {
    return view('cliente.eliminarrut');
});


//**************************************************************/
//Rutas para Categoria
Route::get('/categoria', [CategoriaController::class, 'obtenerCategorias']);
Route::get('/categoria/buscar/id/{id}', [CategoriaController::class, 'obtenerCategoriaPorId']);
Route::get('/categoria/buscar/nombre/{Nombre}', [CategoriaController::class, 'obtenerCategoriaPorNombre']);
Route::get('/categoria/buscar/categoria/{categoria}', [CategoriaController::class, 'obtenerCategoriaPorCategoria']);

Route::get('/categoria/nuevo', function () {
    return view('categoria.agregarcategoria');
});

Route::post('/categoria/agregar', [CategoriaController::class, 'insertarCategoria']);

Route::delete('/categoria/eliminar/id', [CategoriaController::class, 'eliminarCategoriaPorId']);
Route::get('/categoria/view/eliminar/id', function () {
    return view('categoria.eliminarid');
});

// Route::put('/categoria/modificar/{id}',[CategoriaController::class, 'modificarCategoria']);