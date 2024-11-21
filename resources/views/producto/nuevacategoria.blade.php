@extends('layouts.app')
@section('title', 'Agregar Categoria')
@section('maintitle', 'Agregar Categoria')

@section('content')
    <form action="{{ route('producto.ingresarcategoria') }}"  method="POST">
        @csrf
        <center>
            <p><a href="#" 
            onclick="mostrarCategorias()" 
            class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Ver Categorias</a></p>
        </center>
        <br>
        <br>

        <table class="table-light  w-100">
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Nombre
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="text" name="nombre" id="nombre">
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

@vite('resources/js/assets/categoria/categoria.js')


@endsection


