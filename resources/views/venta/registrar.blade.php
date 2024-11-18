@extends('layouts.app')
@section('title', 'Registrar Nueva Venta')
@section('maintitle', 'Registrar Nueva Venta')

@section('content')
    <br>
    <form id="registroVenta" action="{{ route('venta.agregar') }}"  method="POST">
        @csrf
        <table class="table-light  w-100">
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">Fecha y Hora</td>
                <td class="table-light border mx-auto p-2">
                    <div class="d-flex align-items-center gap-2">
                        <input class="form-control" type="date" name="fechaVenta" id="fechaVenta">
                        <input class="form-control" type="time" name="hora" id="hora" value="{{ $horaActual }}">
                    </div>
                </td>
            </tr>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Total Venta
                </td>
                <td class="table-light border mx-auto p-2">
                    <input class="form-control" type="text" name="totalVenta" id="totalVenta" disabled>
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-3">
                    Metodo Pago
                </td>
                <td class="table-light border mx-auto p-2">
                    <select class="form-select" name="metodoPago" id="metodoPago">
                        @foreach($metodospago as $metodo)
                            <option value="{{ $metodo['id'] }}">{{ $metodo['descripcion'] }}</option>
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
                            <input type="number" class="form-control" name="clienteRut" id="clienteRut" min=0 placeholder="Ingrese Rut">
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

        <p class="text-center fs-4 mt-10">Detalle de Ventas</p>
        <table class="table" id="detalleTable">
        <thead>
            <tr>
            <th scope="col">Codigo Producto</th>
            <th scope="col">Producto</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Precio Unitario</th>
            <th scope="col">Subtotal</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- Filas de detalle venta -->
            </tr>      
        </tbody>
        </table>
        <td class="table-light">
                    <button type="button" class="btn btn-success btn-sm" onclick="agregarFilaDetalle()"><i class="fa-solid fa-plus"></i></button>
        </td>
        <div class="mx-auto p-2" style="width: 200px;">
            <input class="btn btn-primary" name="registrar" type="submit" value="Registrar Venta" >
        </div>

    </form>

    <datalist id="productList">
        @foreach($productos as $producto)
            <option value="{{ $producto['nombre'] }}"></option>
        @endforeach
    </datalist>

    <script>
        let productMap = {};
        let clientesMap = {};
        document.addEventListener("DOMContentLoaded", function () {

            let today = new Date().toISOString().split('T')[0];

            // Asigna la fecha de hoy a los campos de fecha
            document.getElementById('fechaVenta').value = today;

            //Lista productos
            const productos = @json($productos);
            const clientes = @json($clientes);
            // Crear un mapa de productos para facilitar la búsqueda
            productos.forEach(producto => {
                productMap[producto.id] = {
                    nombre: producto.nombre,
                    precioVenta: producto.precioVenta,
                    stock: producto.stock
                };
            });

             // Crear un mapa de productos para facilitar la búsqueda
            clientes.forEach(cliente => {
                clientesMap[cliente.rut] = {
                    nombre: cliente.nombre,
                };
            }); 
            agregarFilaDetalle();
        });



    </script>


    @vite('resources/js/assets/ventas/registrar.js')



@endsection
