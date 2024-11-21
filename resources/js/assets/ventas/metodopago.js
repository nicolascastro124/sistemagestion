export function mostrarMetodosPago(event, id){
    let detallesHtml = `
    <table class="table">
      <thead>
        <tr>
        <th scope="col">ID</th>
        <th scope="col">Descripcion</th>
        </tr>
    </thead>
    <tbody>
    `;
    fetch(`/ventas/metodospago`).then(response => response.json())
    .then(data => {
        // HTML para todos los detalles
        data.forEach(metodo => {
            detallesHtml += `
                <tr>
                <td>${metodo.id}</td>
                <td>${metodo.descripcion}</td>
                </tr>
            `;
        });

        detallesHtml += `
                </tbody>
                </table>`;
            Swal.fire({
                title: 'Metodos de Pagos',
                html: detallesHtml,
                icon: 'info',
                confirmButtonText: 'Aceptar'
            });
        })
}

window.mostrarMetodosPago = mostrarMetodosPago;
