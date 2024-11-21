@extends('layouts.app')
@section('title', 'Categorias por Rango Fechas')
@section('maintitle', 'Categorias por Rango Fechas')

@section('content')

<form action="{{ route('info.categoriaproductosgenerar')}}"  method="POST">
        @csrf
        <center>
            <div class="input-group mb-3">
                <input type="date" class="form-control" name="fechaInicio" id="fechaInicio">
                <span class="input-group-text"></span>
                <input type="date" class="form-control" name="fechaTermino" id="fechaTermino">
            </div>
            <input class="btn btn-light" name="generar" type="submit" value="Generar" >
        </center>
</form>


@endsection
