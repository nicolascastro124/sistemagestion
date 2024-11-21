@extends('layouts.app')
@section('title', 'Agregar Metodo Pago')
@section('maintitle', 'Agregar Metodo Pago')

@section('content')
    <form action="{{ route('venta.ingresarmetodo') }}"  method="POST">
        @csrf
        <center>
            <p><a href="#" 
            onclick="mostrarMetodosPago()" 
            class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Ver Metodos de Pago</a></p>
        </center>
        <br>
        <br>

        <table class="table-light  w-100">
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Descripcion
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="text" name="descripcion" id="descripcion">
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

@vite('resources/js/assets/ventas/metodopago.js')


@endsection


