@extends('layouts.app')
@section('title', 'Editar Venta')
@section('maintitle', 'Editar Venta')

@section('content')

@php
    $fila = count($detalles); 
@endphp


<script>

let fila = {{ $fila }};
const productos = @json($productos);
const clientes = @json($clientes);

</script>

<script src="{{ asset('js/assets/ventas/functions.js') }}"></script>

@vite('resources/js/assets/ventas/modificar.js')

    <form id="registroVenta" action="{{ route('venta.actualizar') }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $venta['id'] }}">

        <!-- Información de la venta -->
        <table class="table-light  w-100">
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">Fecha y Hora</td>
                <td class="table-light border mx-auto p-2">
                    <div class="d-flex align-items-center gap-2">
                        <input class="form-control" type="date" name="fechaVenta" id="fechaVenta" value="{{ $venta['fecha'] }}">
                        <input class="form-control" type="time" name="hora" id="hora" value={{ $venta['hora'] }}>
                    </div>
                </td>
            </tr>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Total Venta
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="text" name="totalVenta" id="totalVenta" value="{{ $venta['totalVenta'] }}" disabled >
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Metodo Pago
                </td>
                <td class="table-light border mx-auto p-2">
                    <select class="form-select" name="metodoPago" id="metodoPago">
                        @foreach($metodospago as $metodo)
                            <option value="{{ $metodo['id'] }}" {{ $venta['idMetodoPago'] == $metodo['id'] ? 'selected' : '' }}>
                                {{ $metodo['descripcion'] }}
                            </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Rut Cliente (Sin DV)
                </td>
                <td class="table-light border mx-auto p-2">
                    <div class="row">
                        <div class="col">
                            <input type="number" class="form-control" name="clienteRut" id="clienteRut" min=0 placeholder="Ingrese Rut" value="{{ $venta['rutCliente'] }}">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" name="clienteNombre" id="clienteNombre" placeholder="Nombre" disabled>
                        </div>
                    </div>
                </td>

            </tr>
            <tr class="table-light float-center">
                <td class="table-light">
                </td>

            </tr>
        </table>

        <!-- Detalles de la venta -->
        <p class="text-center fs-4 mt-10">Detalle de Ventas</p>
        <table class="table" id="detalleTable">
        <thead>
            <tr>
            <th scope="col">Codigo Producto</th>
            <th scope="col">Producto</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Precio Unitario</th>
            <th scope="col">Subtotal</th>
            </tr>
        </thead>
            @php
                $fila = 0; 
            @endphp
            <tbody>
                @foreach($detalles as $detalle)
                    <tr>
                    </tr>
                    <script>
                        var id = {{ $detalle['id'] }};
                        var idProductoDetalle = {{$detalle['idProducto']}};
                        var nombreProductoDetalle = getNombreProducto(idProductoDetalle)
                        var cantidadDetalle = {{$detalle['cantidad']}};
                        var subtotalDetalle = {{$detalle['subtotal']}};

                        datosDetalle(id,idProductoDetalle,nombreProductoDetalle,cantidadDetalle,subtotalDetalle,{{$fila}}) 


                    </script>
                    @php
                        $fila++; 
                    @endphp
                @endforeach

            </tbody>

        </table>

        <div class="mx-auto p-2" style="width: 200px;">
            <input class="btn btn-primary" name="registrar" type="submit" value="Actualizar Venta" >
        </div>


    </form>

    <datalist id="productList">
        @foreach($productos as $producto)
            <option value="{{ $producto['nombre'] }}"></option>
        @endforeach
    </datalist>


@endsection
