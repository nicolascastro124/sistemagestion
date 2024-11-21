<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;

class HomeController
{
    protected $productoController ;
    protected $ventaController ;

    public function __construct()
    {
        // Inicializar CategoriaController en el constructor
        $this->productoController = new ProductoController();
        $this->ventaController = new VentaController();

    }

    public function welcome(){
        $topProduct = $this->productoController->productoMasVendido();
        $menorStock = $this->productoController->menorStock();
        $ventasHoy = $this->ventaController->cantidadVentasHoy();

        return view('welcome', compact('topProduct','ventasHoy','menorStock'));
    }

}
