@extends('layouts.app')
@section('title', 'Agregar Stock Producto')
@section('maintitle', 'Agregar Stock Producto')

@section('content')
<div class="container mt-4">
    <!-- Formulario para agregar stock -->
    <form action="{{ route('producto.agregarStock') }}" method="POST">
        @csrf

        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Mensaje de error -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <table class="table table-bordered w-100">
            <!-- Fila: Producto -->
            <tr>
                <td class="text-end fw-bold align-middle p-3">Producto</td>
                <td class="p-2">
                    <select class="form-select" name="producto" id="producto" required>
                        <option value="" disabled selected>Seleccione un producto</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto['id'] }}">{{ $producto['nombre'] }} (Stock actual: {{ $producto['stock'] }})</option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <!-- Fila: Cantidad -->
            <tr>
                <td class="text-end fw-bold align-middle p-3">Cantidad</td>
                <td class="p-2">
                    <input class="form-control" type="number" name="cantidad" id="cantidad" min="1" placeholder="Ingrese cantidad" required>
                </td>
            </tr>

            <!-- Botón de Registrar -->
            <tr>
                <td colspan="2" class="text-center">
                    <button type="submit" class="btn btn-light">Registrar</button>
                </td>
            </tr>
        </table>
    </form>
</div>
@endsection
