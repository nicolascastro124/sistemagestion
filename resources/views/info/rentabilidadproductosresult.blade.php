@extends('layouts.app')
@section('title', 'Rentabilidad Productos')
@section('maintitle', 'Rentabilidad Productos Fechas')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-0">
        <p class="h5 mb-0">Rentabilidad De Productos Entre {{ $fechaInicio }} y {{ $fechaFin }}</p>
        <div>
            <form method="POST" action="{{ route('info.rentabilidadproductos.excel') }}" class="mb-0">
                @csrf
                <input type="hidden" name="fechaInicio" value="{{ $fechaInicio }}">
                <input type="hidden" name="fechaTermino" value="{{ $fechaFin }}">
                <button type="submit" class="btn btn-success btn-sm" style="min-width: 120px;">Exportar Excel</button>
            </form>
        </div>
    </div>

    <!-- Tabla de datos -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad Vendida</th>
                <th>Costo Total</th>
                <th>Ingreso Total</th>
                <th>Ganancia Total</th>
                <th>Margen Rentabilidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->producto }}</td>
                    <td>{{ $producto->cantidad_vendida }}</td>
                    <td>{{ $producto->costo_total }}</td>
                    <td>{{ $producto->ingreso_total }}</td>
                    <td>{{ $producto->ganancia_total }}</td>
                    <td>{{ $producto->margen_rentabilidad }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
