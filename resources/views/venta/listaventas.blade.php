@extends('layouts.app')
@section('title', 'Lista de Ventas')
@section('maintitle', 'Lista de Ventas')

@section('content')

<div class="container my-4">
    <div class="row mb-3">
        <div class="col-md-6 d-flex align-items-center">
            <label for="startDate" class="me-2 text-nowrap">Fecha de inicio:</label>
            <input type="date" id="startDate" class="form-control" placeholder="Fecha de inicio">
        </div>
        <div class="col-md-6 d-flex align-items-center">
            <label for="endDate" class="me-2 text-nowrap">Fecha de fin:</label>
            <input type="date" id="endDate" class="form-control" placeholder="Fecha de fin">
        </div>
        <div class="col-md-6 mt-3 d-flex align-items-center">
            <label for="searchInput" class="me-2 text-nowrap">Cliente:</label>
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por Cliente...">
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Total Venta</th>
                <th>Metodo Pago</th>
                <th>Cliente </th>
                <th>Detalle</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="ventasTable">
            @foreach($ventas as $venta)
                <tr>
                    <td>{{ $venta['fecha'] }} {{ $venta['hora'] }}</td>
                    <td>${{ number_format($venta['totalVenta'], 0, ',', '.') }}</td>
                    <td class="category">{{ $venta['nombreMetodoPago'] }}</td>
                    <td class="category">{{ $venta['nombreCliente'] }}</td>

                    <td>
                        <p><a href="#" 
                        onclick="mostrarDetalleVenta(event,{{ $venta['id'] }})" 
                        class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Ver Detalle</a></p>
                    </td>
                    <td>
                        <a href="{{ route('venta.editar', $venta['id']) }}" class="btn btn-success">Editar <i class="fa-solid fa-pen"></i></a>
                    </td>
                    <td>
                        <a href="#" class="btn btn-danger" 
                            onclick="confirmarEliminacion('{{ route('venta.eliminar', $venta['id']) }}'); return false;">
                            Eliminar <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Formulario oculto para eliminación -->
<form id="deleteForm" action="" method="POST" hidden>
    @csrf
    @method('DELETE')
</form>




<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Obtiene la fecha de hoy
        let today = new Date().toISOString().split('T')[0];

        // Asigna la fecha de hoy a los campos de fecha
        document.getElementById('startDate').value = today;
        document.getElementById('endDate').value = today;
        filterTable();
    });

document.getElementById('startDate').addEventListener('change', filterTable);
document.getElementById('endDate').addEventListener('change', filterTable);
document.getElementById('searchInput').addEventListener('keyup', filterTable);

function filterTable() {
    // Obtener valores de los campos de búsqueda
    let startDate = document.getElementById('startDate').value;
    let endDate = document.getElementById('endDate').value;
    let searchValue = document.getElementById('searchInput').value.toLowerCase();

    // Seleccionar todas las filas de la tabla
    let rows = document.querySelectorAll('#ventasTable tr');

    rows.forEach(row => {
        // Obtener valores
        let fechaVentaCompleta = row.cells[0].textContent.trim();
        let fechaVenta = fechaVentaCompleta.split(' ')[0]; // Fecha sin la hora
        let nombreCliente = row.cells[3].textContent.trim().toLowerCase();

        let mostrarFila = true;

        // Filtrar por rango de fechas
        if (startDate && fechaVenta < startDate) {
            mostrarFila = false;
        }
        if (endDate && fechaVenta > endDate) {
            mostrarFila = false;
        }

        // Filtrar por nombre de cliente 
        if (searchValue && !nombreCliente.includes(searchValue)) {
            mostrarFila = false;
        }

        if (mostrarFila) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

</script>
@vite('resources/js/assets/ventas/ventas.js')

@endsection


