@extends('layouts.app')
@section('title', 'Lista de Productos')
@section('maintitle', 'Lista de Productos')

@section('content')

<div class="container my-4">
    <div class="row mb-3">

        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar producto...">
        </div>
        <div class="col-md-6">
            <select id="categoryFilter" class="form-select">
                <option value="">Todas</option>
                @foreach($categorias as $categoria)
                    <option value="{{ strtolower($categoria->nombre) }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Costo</th>
                <th>Precio de Venta</th>
                <th>Stock</th>
                <th>Fecha de Vencimiento</th>
                <th>Categoría</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="productTable">
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto['nombre'] }}</td>
                    <td>{{ $producto['costo'] }}</td>
                    <td>{{ $producto['precioVenta'] }}</td>
                    <td>{{ $producto['stock'] }}</td>
                    <td>{{ $producto['fechaVencimiento'] ?? 'N/A' }}</td>
                    <td class="category">{{ $producto['nombreCategoria'] }}</td>
                    <td>
                        <a href="{{ route('producto.editar', $producto['id']) }}" class="btn btn-success">Editar <i class="fa-solid fa-pen"></i></a>
                    </td>
                    <td>
                        <a href="#" class="btn btn-danger" 
                           onclick="confirmarEliminacion('{{ route('producto.eliminar', $producto['id']) }}'); return false;">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        let searchValue = document.getElementById('searchInput').value.toLowerCase();
        let selectedCategory = document.getElementById('categoryFilter').value.toLowerCase();
        let rows = document.querySelectorAll('#productTable tr');

        rows.forEach(row => {
            let productName = row.cells[0].textContent.toLowerCase();
            let productCategory = row.querySelector('.category').textContent.toLowerCase();

            let matchesSearch = productName.includes(searchValue);
            let matchesCategory = selectedCategory === "" || productCategory.includes(selectedCategory);

            if (matchesSearch && matchesCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

@endsection
