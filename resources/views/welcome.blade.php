@extends('layouts.app')
@section('title', 'Bienvenida')
@section('maintitle', 'Bienvenido/a ' . auth()->user()->name)

@section('content')

<div class="container mt-4">
    <div class="row align-items-center justify-content-between">
        <!-- izquierda -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body bg-light">
                    <h4 class="card-title">Producto Más Vendido Hoy</h4>
                    @if ($topProduct)
                        <p class="card-text">
                            <b>{{ $topProduct->producto }}</b> con un total de <b>{{ $topProduct->total_vendido }} unidades </b> vendidas.
                        </p>
                    @else
                        <p class="card-text">No se encontraron ventas para el día de hoy.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body bg-light">
                    <h4 class="card-title">Ventas Hoy</h4>
                    @if ($ventasHoy)
                        <p class="card-text">
                            Se han realizado un total de <b>{{ $ventasHoy->cantidad_ventas_hoy }} ventas</b>, por un total de
                            ${{ number_format($ventasHoy->total_ventas_hoy, 0, ',', '.') }}
                        </p>
                    @else
                        <p class="card-text">No se encontraron ventas para el día de hoy.</p>
                    @endif
                </div>
            </div>
        </div>

        
        <!-- derecha -->
        <div class="col-md-6">
            @if ($menorStock)
                <h4 class="mb-3 text-center h5">Productos con Menor Stock</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody id="productTable">
                            @foreach($menorStock as $producto)
                                <tr>
                                    <td>{{ $producto->producto }}</td>
                                    <td>{{ $producto->stock }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
