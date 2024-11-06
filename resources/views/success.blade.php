@extends('layouts.app')
@section('title', 'Success')
@section('maintitle', '')

@section('content')
    <div class="container text-center">
        <br>
        <p class="fs-4">{{ $message }}</p>
        <br>
        <a href="{{ $url }}" class="btn btn-primary">Volver</a>
    </div>
@endsection
