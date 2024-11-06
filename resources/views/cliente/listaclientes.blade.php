@extends('layouts.app')
@section('title', 'Lista de Clientes')
@section('maintitle', 'Lista de Clientes')

@section('content')

<div class="container my-4">
    <div class="row mb-3">

        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar Cliente...">
        </div>
        <div class="col-md-6">
            <select id="categoryFilter" class="form-select">
                <option value="nombre" selected>Nombre</option>
                <option value="rut">Rut</option>
            </select>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Rut</th>
                <th>Telefono</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="clientesTable">
            @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente['nombre'] }}</td>
                    <td>{{ $cliente['rut'] }}-{{ $cliente['dv'] }}</td>
                    <td>{{ $cliente['telefono'] }}</td>
                    <td>
                        <a href="{{ route('cliente.editar', $cliente['id']) }}" class="btn btn-success">Editar <i class="fa-solid fa-pen"></i></a>
                    </td>
                    <td>
                        <a href="#" class="btn btn-danger" 
                           onclick="confirmarEliminacion('{{ route('cliente.eliminar', $cliente['id']) }}'); return false;">
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
    function confirmarEliminacion(url) {
        Swal.fire({
            title: '¿Desea eliminar este registro?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('deleteForm');
                form.action = url;
                form.submit();
            }
        });
    }

    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('categoryFilter').addEventListener('change', filterTable);

    function filterTable() {
        var selectedValue = document.getElementById('categoryFilter').value.toLowerCase();
        let searchValue = document.getElementById('searchInput').value.toLowerCase();

        document.getElementById('categoryFilter').addEventListener("change", function(){
            var selectedValue = this.value;
        });

        let rows = document.querySelectorAll('#clientesTable tr');
        rows.forEach(row => {

            if (selectedValue == 'nombre'){
                let clienteName = row.cells[0].textContent.toLowerCase();
                let matchesSearch = clienteName.includes(searchValue);
                if (matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
            else if (selectedValue == 'rut'){
                let clienteRut = row.cells[1].textContent.toLowerCase();
                let matchesSearch = clienteRut.includes(searchValue);
                if (matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }  
            }      


        });
    }
</script>

@endsection
