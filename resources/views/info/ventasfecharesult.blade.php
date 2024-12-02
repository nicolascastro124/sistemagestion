@extends('layouts.app')
@section('title', 'Ventas por Fechas')
@section('maintitle', 'Ventas por Rango Fechas')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-0">
        <p class="h5 mb-0">Ventas Entre {{ $fechaInicio }} y {{ $fechaFin }}</p>
        <div>
            <form method="POST" action="{{ route('info.ventasfecha.excel') }}" class="mb-0">
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
                <th>Fecha</th>
                <th>Total Ventas Diarias</th>
                <th>Cantidad Ventas Diarias</th>
                <th>Producto Más Vendido</th>
                <th>Cantidad Vendida</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($totales as $total)
                <tr>
                    <td>{{ $total->fecha }}</td>
                    <td>{{ $total->total_ventas_diarias }}</td>
                    <td>{{ $total->cantidad_ventas_diarias }}</td>
                    <td>{{ $total->producto_mas_vendido }}</td>
                    <td>{{ $total->cantidad_venta_producto }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
