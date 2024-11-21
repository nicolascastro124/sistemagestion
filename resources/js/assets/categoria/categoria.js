export function mostrarCategorias(event, id){
    let detallesHtml = `
    <table class="table">
      <thead>
        <tr>
        <th scope="col">ID</th>
        <th scope="col">Nombre Categoria</th>
        </tr>
    </thead>
    <tbody>
    `;
    fetch(`/producto/categorias`).then(response => response.json())
    .then(data => {
        // HTML para todos los detalles
        data.forEach(categoria => {
            detallesHtml += `
                <tr>
                <td>${categoria.id}</td>
                <td>${categoria.nombre}</td>
                </tr>
            `;
        });

        detallesHtml += `
                </tbody>
                </table>`;
            Swal.fire({
                title: 'Categorias de Productos',
                html: detallesHtml,
                icon: 'info',
                confirmButtonText: 'Aceptar'
            });
        })
}

window.mostrarCategorias = mostrarCategorias;
