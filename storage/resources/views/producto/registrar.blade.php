@extends('layouts.app')
@section('title', 'Agregar Producto')
@section('maintitle', 'Agregar Producto')

@section('content')
    <br>
    <form action="{{ route('producto.agregar') }}"  method="POST">
        @csrf
        <table class="table-light  w-100">
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Nombre
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="text" name="nombre" id="nombre">
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Categoria
                </td>
                <td class="table-light border mx-auto p-2">
                    <select class="form-select" name="categoria" id="categoria">
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria['id'] }}">{{ $categoria['nombre'] }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Costo
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="number" name="costo" id="costo" min=1 >
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Precio Venta
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="number" name="precioventa" id="precioventa" min=1 >
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Stock
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="number" name="stock" id="stock" min=1 >
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Fecha Vencimiento
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="date" name="fechavenc" id="fechavenc" >
                </td>
            </tr>
            <tr class="table-light float-center">
                <td class="table-light">
                </td>
                <td class="table-light">
                    <input class="btn btn-light" name="registrar" type="submit" value="Registrar" >
                </td>
            </tr>
        </table>
    </form>
@endsection
