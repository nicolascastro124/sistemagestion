@extends('layouts.app')
@section('title', 'Editar Producto')
@section('maintitle', 'Editar Producto')

@section('content')
    <br>
    <form action="{{ route('producto.actualizar', $producto['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <input class="form-control" type="number" name="id" id="id" value="{{ $producto['id'] }}" hidden>
        <table class="table-light w-100">
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">Nombre</td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="text" name="nombre" id="nombre" value="{{ $producto['nombre'] }}">
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">Categoria</td>
                <td class="table-light border mx-auto p-2">
                    <select class="form-select" name="idCategoria" id="categoria">
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria['id'] }}" {{ $producto['idCategoria'] == $categoria['id'] ? 'selected' : '' }}>
                                {{ $categoria['nombre'] }}
                            </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">Costo</td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="number" name="costo" id="costo" min=1 value="{{ $producto['costo'] }}">
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">Precio Venta</td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="number" name="precioVenta" id="precioVenta" min=1 value="{{ $producto['precioVenta'] }}">
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">Stock</td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="number" name="stock" id="stock" min=1 value="{{ $producto['stock'] }}">
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">Fecha Vencimiento</td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="date" name="fechaVencimiento" id="fechaVencimiento" value="{{ $producto['fechaVencimiento'] }}">
                </td>
            </tr>
            <tr class="table-light float-center">
                <td class="table-light"></td>
                <td class="table-light">
                    <input class="btn btn-light" name="actualizar" type="submit" value="Actualizar">
                </td>
            </tr>
        </table>
    </form>
@endsection
