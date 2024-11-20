<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\MetodoPagoController;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;

//Ruta creada por laravel


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
Route::match(['get', 'post'], '/productos' , [ProductoController::class, 'obtenerProductos'])->name('producto.listaproductos')->middleware('auth'); //Lista
Route::get('/producto/nuevo', [ProductoController::class, 'nuevoProducto'])->name('producto.registrar')->middleware('auth'); //Lista
Route::post('/producto/agregar', [ProductoController::class, 'insertarProducto'])->name('producto.agregar')->middleware('auth'); // Listo

Route::get('/producto/editar/{id}', [ProductoController::class, 'editarProductoVista'])->name('producto.editar')->middleware('auth'); //Listo
Route::put('/producto/actualizar/', [ProductoController::class, 'modificarProducto'])->name('producto.actualizar')->middleware('auth'); //Listo

Route::delete('/producto/eliminar/{id}', [ProductoController::class, 'eliminarProductoPorId'])  //Listo
->where('id', '[0-9]+')
->name('producto.eliminar')
->middleware('auth');


Route::get('/producto/lista', [ProductoController::class,'obtenerProductosLista'])->middleware('auth');

//**************************************************************/
//Rutas para cliente
Route::get('/clientes', [ClienteController::class, 'obtenerClientes'])->name('cliente.listaclientes')->middleware('auth'); // Listo
Route::get('/cliente/nuevo', [ClienteController::class, 'nuevoCliente'])->name('cliente.registrar')->middleware('auth'); // Listo
Route::post('/cliente/agregar', [ClienteController::class, 'insertarCliente'])->name('cliente.agregar')->middleware('auth'); // Listo

Route::get('/cliente/editar/{id}', [ClienteController::class, 'editarClienteVista'])->name('cliente.editar')->middleware('auth'); //Listo
Route::put('/cliente/actualizar/', [ClienteController::class, 'modificarCliente'])->name('cliente.actualizar')->middleware('auth'); //Listo

Route::delete('/cliente/eliminar/{id}', [ClienteController::class, 'eliminarClientePorId'])  //Listo
->where('id', '[0-9]+')
->name('cliente.eliminar')
->middleware('auth');



//**************************************************************/
//Rutas para Categoria
Route::get('/categoria/nuevo', [CategoriaController::class, 'nuevaCategoria'])->name('categoria.registrar')->middleware('auth');


//**************************************************************/
//Rutas para Venta
Route::get('/ventas', [VentaController::class, 'obtenerVentas'])->name('venta.lista')->middleware('auth'); //Listo
Route::get('/ventas/detalle/{id}', [VentaController::class, 'obtenerDetalle'])->name('venta.detalle')->middleware('auth'); // Listo

Route::get('/ventas/nueva', [VentaController::class, 'nuevaVenta'])->name('venta.registrar')->middleware('auth');  // Listo
Route::post('/ventas/agregar', [VentaController::class, 'insertaVenta'])->name('venta.agregar')->middleware('auth'); // Listo
Route::delete('/ventas/eliminar/{id}', [VentaController::class, 'eliminarVentaPorId'])  //Listo
->where('id', '[0-9]+')
->name('venta.eliminar')
->middleware('auth');

Route::get('/ventas/editar/{id}', [VentaController::class, 'editarVentaVista'])->name('venta.editar')->middleware('auth'); //Listo
Route::put('/ventas/actualizar/', [VentaController::class, 'modificarVenta'])->name('venta.actualizar')->middleware('auth'); //Listo



Route::get('/metodopago', [MetodoPagoController::class, 'obtenerMetodosPago'])->name('metodopago.lista')->middleware('auth');

//**************************************************************/

Route::get('/generate-hash', function () {
    return Hash::make('995578651');
});


//**************************************************************/


// Ruta para formulario de login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Ruta para procesar el login
Route::post('/login', [AuthController::class, 'login']);

// Ruta para cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// requiere autenticación
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

});

// formulario de registro
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

// procesar registro
Route::post('/register', [AuthController::class, 'register']);

// procesar registro
Route::post('/register', [AuthController::class, 'register']);

//**************************************************************/
//Rutas para usuarios
Route::get('/users', [AuthController::class, 'usersList'])->name('users')->middleware('auth');
Route::put('/users/{id}', [AuthController::class, 'update'])->name('users.update')->middleware('auth');
Route::put('/users/eliminar/{id}', [AuthController::class, 'deactivate'])->name('users.deactivate')->middleware('auth');
