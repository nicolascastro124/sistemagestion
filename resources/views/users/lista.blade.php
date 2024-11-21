@extends('layouts.app')
@section('title', 'Lista de Usuarios')
@section('maintitle', 'Lista de Usuarios')

@section('content')


    <div class="container mt-4">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>RUT</th>
                    <th>Rol de Administrador</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->rut }}</td>
                        <td>{{ $user->is_admin ? 'Sí' : 'No' }}</td>
                        <td>{{ $user->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <button class="btn btn-success btn-sm me-3" onclick="editUserData({{ json_encode($user) }})">
                                Editar <i class="fa-solid fa-pen"></i>
                            </button>
                        @if ($user->id != 1)
                            <button class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }})">
                                Eliminar <i class="fa-solid fa-trash"></i>
                            </button>
                        @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/users/modificar.js') }}"></script>
    <script>
        window.csrfToken = '{{ csrf_token() }}';
    </script>

@endsection


