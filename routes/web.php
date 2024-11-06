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
Route::get('/producto/nuevo', [ProductoController::class, 'nuevoProducto'])->name('producto.registrar'); //Lista
Route::post('/producto/agregar', [ProductoController::class, 'insertarProducto'])->name('producto.agregar'); // Listo

Route::get('/producto/editar/{id}', [ProductoController::class, 'editarProductoVista'])->name('producto.editar'); //Listo
Route::put('/producto/actualizar/', [ProductoController::class, 'modificarProducto'])->name('producto.actualizar'); //Listo

Route::delete('/producto/eliminar/{id}', [ProductoController::class, 'eliminarProductoPorId'])  //Listo
->where('id', '[0-9]+')
->name('producto.eliminar');

//**************************************************************/
//Rutas para cliente
Route::get('/clientes', [ClienteController::class, 'obtenerClientes'])->name('cliente.listaclientes'); // Listo
Route::get('/cliente/nuevo', [ClienteController::class, 'nuevoCliente'])->name('cliente.registrar'); // Listo
Route::post('/cliente/agregar', [ClienteController::class, 'insertarCliente'])->name('cliente.agregar'); // Listo

Route::get('/cliente/editar/{id}', [ClienteController::class, 'editarClienteVista'])->name('cliente.editar'); //Listo
Route::put('/cliente/actualizar/', [ClienteController::class, 'modificarCliente'])->name('cliente.actualizar'); //Listo

Route::delete('/cliente/eliminar/{id}', [ClienteController::class, 'eliminarClientePorId'])  //Listo
->where('id', '[0-9]+')
->name('cliente.eliminar');



//**************************************************************/
//Rutas para Categoria
Route::get('/categoria/nuevo', [CategoriaController::class, 'nuevaCategoria'])->name('categoria.registrar');


