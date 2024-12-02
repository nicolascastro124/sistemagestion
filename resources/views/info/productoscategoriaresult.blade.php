@extends('layouts.app')
@section('title', 'Categorias por Rango Fechas')
@section('maintitle', 'Categorias por Rango Fechas')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-0">
        <p class="h5 mb-0">Productos Por Categoria Entre {{ $fechaInicio }} y {{ $fechaFin }}</p>
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
                <th>Categoria</th>
                <th>Cantidad Vendida</th>
                <th>Total Ventas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->categoria }}</td>
                    <td>{{ $producto->cantidad_vendida }}</td>
                    <td>{{ $producto->total_ventas }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
