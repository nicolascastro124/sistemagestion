function editUserData(user) {
    Swal.fire({
        title: `Editar Usuario: ${user.name}`,
        html: `
            <form id="edit-user-form">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" id="name" class="form-control" value="${user.name}">
                </div>
                <div class="mb-3">
                    <label for="is_admin" class="form-label">Rol de Administrador</label>
                    <select id="is_admin" class="form-control">
                        <option value="1" ${user.is_admin ? 'selected' : ''}>Sí</option>
                        <option value="0" ${!user.is_admin ? 'selected' : ''}>No</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Nueva Contraseña (opcional)</label>
                    <input type="password" id="password" class="form-control">
                </div>
            </form>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            return {
                id: user.id,
                name: document.getElementById('name').value,
                is_admin: document.getElementById('is_admin').value,
                password: document.getElementById('password').value, 
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            updateUser(result.value);
        }
    });
}


function updateUser(data) {
    fetch(`/users/${data.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            Swal.fire('Actualizado', 'El usuario ha sido actualizado con éxito.', 'success')
                .then(() => location.reload());
        } else {
            Swal.fire('Error', 'Hubo un problema al actualizar el usuario.', 'error');
        }
    })
    .catch(() => {
        Swal.fire('Error', 'No se pudo completar la operación.', 'error');
    });
}

function deleteUser(userId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'El usuario será desactivado y ya no estará activo.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/users/eliminar/${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    Swal.fire(
                        'Eliminado',
                        'El usuario ha sido desactivado.',
                        'success'
                    ).then(() => location.reload());
                } else {
                    Swal.fire('Error', 'No se pudo completar la operación.', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'No se pudo completar la operación.', 'error');
            });
        }
    });
}
