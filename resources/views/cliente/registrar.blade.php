@extends('layouts.app')
@section('title', 'Agregar Cliente')
@section('maintitle', 'Agregar Cliente')

@section('content')
    <br>
    <form action="{{ route('cliente.agregar') }}"  method="POST">
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
                    RUT (Sin Digito Verificador)
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="number" name="rut" id="rut" min=1 >
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Telefono
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="number" name="telefono" id="telefono" >
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
