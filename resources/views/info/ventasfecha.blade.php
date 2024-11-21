@extends('layouts.app')
@section('title', 'Ventas por Fechas')
@section('maintitle', 'Ventas por Rango Fechas')

@section('content')

<form action="{{ route('info.ventasfechagenerar')}}"  method="POST">
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

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@endsection
