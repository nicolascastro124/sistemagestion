@extends('layouts.app')
@section('title', 'Agregar Categoria')
@section('maintitle', 'Agregar Categoria')

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
        </table>
    </form>
@endsection
