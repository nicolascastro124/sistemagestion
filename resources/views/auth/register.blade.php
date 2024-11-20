@extends('layouts.app')
@section('title', 'Registro de Usuarios')
@section('maintitle', 'Registro de Usuarios')

@section('content')

<form action="{{ route('register') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-2">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="col-md-6 mb-2">
            <label for="rut" class="form-label">RUT</label>
            <input type="text" class="form-control" id="rut" name="rut" value="{{ old('rut') }}" required>
            <small class="form-text text-muted">Sin DV, Sin Puntos ni Guión</small>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-2">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="col-md-6 mb-2">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
    </div>

    <div class="form-check mb-3">
        <input type="hidden" name="is_admin" value="0">
        <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" value="1">
        <label class="form-check-label" for="is_admin">Rol de administrador</label>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </div>
</form>

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@endsection

