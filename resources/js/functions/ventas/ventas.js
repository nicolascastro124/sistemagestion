// resources/js/functions/ventas.js

export function mostrarDetalleVenta(event, id){
    let detallesHtml = `
    <table class="table">
      <thead>
        <tr>
        <th scope="col">Producto</th>
        <th scope="col">Cantidad</th>
        <th scope="col">Subtotal</th>
        </tr>
    </thead>
    <tbody>
    `;
    event.preventDefault(); // evitar comportamiento predeterminado del botón
    fetch(`/ventas/detalle/${id}`).then(response => response.json())
    .then(data => {
        // HTML para todos los detalles
        data.forEach(detalle => {
            detallesHtml += `
                <tr>
                <td>${detalle.nombreProducto}</td>
                <td>${detalle.cantidad}</td>
                <td>$${detalle.subtotal.toLocaleString()}</td>
                </tr>
            `;
        });

        detallesHtml += `
                </tbody>
                </table>`;
            Swal.fire({
                title: 'Detalle de la Venta',
                html: detallesHtml,
                icon: 'info',
                confirmButtonText: 'Aceptar'
            });
        })


}

export function confirmarEliminacion(url) {
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

window.mostrarDetalleVenta = mostrarDetalleVenta;
window.confirmarEliminacion = confirmarEliminacion;
