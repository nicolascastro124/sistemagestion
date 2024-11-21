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

<script>
    function validarFechas(event) {
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaTermino = document.getElementById('fechaTermino').value;

        // Verificar si ambas fechas están llenas
        if (!fechaInicio || !fechaTermino) {
            alert('Por favor, selecciona ambas fechas.');
            return false;
        }

        // Comparar fechas
        if (new Date(fechaTermino) < new Date(fechaInicio)) {
            alert('La fecha de término debe ser mayor o igual a la fecha de inicio.');
            return false; 
        }

        return true; 
    }
</script>

@endsection
