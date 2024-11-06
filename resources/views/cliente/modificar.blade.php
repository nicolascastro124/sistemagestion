@extends('layouts.app')
@section('title', 'Modificar Cliente')
@section('maintitle', 'Modificar Cliente')

@section('content')
    <br>
    <form action="{{ route('cliente.actualizar', $cliente['id']) }}"  method="POST">
        @csrf
        @method('PUT')
        <input class="form-control" type="number" name="id" id="id" value="{{ $cliente['id'] }}" hidden>
        <table class="table-light  w-100">
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Nombre
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="text" name="nombre" id="nombre" value="{{ $cliente['nombre'] }}">
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    RUT (Sin Digito Verificador)
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="number" name="rut" id="rut" min=1 value="{{ $cliente['rut'] }}">
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Telefono
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="number" name="telefono" id="telefono" value="{{ $cliente['telefono'] }}">
                </td>
            </tr>
            <tr class="table-light float-center">
                <td class="table-light">
                </td>
                <td class="table-light">
                    <input class="btn btn-light" name="registrar" type="submit" value="Actualizar" >
                </td>
            </tr>
        </table>
    </form>
@endsection
