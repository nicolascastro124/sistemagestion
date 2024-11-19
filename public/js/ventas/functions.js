function formatearValores(valor) {
    // Convertimos el valor a un número y luego lo formateamos como CLP
    let valorFormateado = new Intl.NumberFormat("es-CL", {
        style: "currency",
        currency: "CLP",
        minimumFractionDigits: 0 // no usa decimales generalmente
    }).format(valor);

    // Actualizamos el campo de input con el valor formateado
    return valorFormateado;
}

function getNombreProducto(idProducto) {
    const producto = productos.find(p => p.id === idProducto);
    return producto ? producto.nombre : "Producto no encontrado";
}

function datosDetalle(id,codigo,producto,cantidad,subtotal,fila) {
    const table = document.getElementById('detalleTable').querySelector('tbody');
    const idCodigoProducto = `codigoProducto_${fila}`;
    const idProducto = `producto_${fila}`;
    const idCantidad = `cantidad_${fila}`;
    const idUnitario = `precioUnitario_${fila}`
    const idSubtotal = `subtotal_${fila}`;
    const idMsg = `msg_${fila}`;
    const idc = `cant_${fila}`;
    const idv = `idv_${fila}`;
    const precioUnitario =  subtotal / cantidad;
    const valorSubtotal = formatearValores(subtotal);
    console.log(id);
    valorHtml =  `
        <td>
            <input class="form-control" id="${idCodigoProducto}" name="detalles[${fila}][codigo_producto]" placeholder="Ingrese Codigo" value="${codigo}" disabled>
            <input class="form-control" id="${idCodigoProducto}" name="detalles[${fila}][codigo_producto]" placeholder="Ingrese Codigo" value="${codigo}" required hidden>

        </td>
        <td>
            <input class="form-control" id="${idProducto}" name="detalles[${fila}][producto]" list="productList" placeholder="Ingrese Producto" value="${producto}" disabled>
            <input class="form-control" id="${idProducto}" name="detalles[${fila}][producto]" list="productList" placeholder="Ingrese Producto" value="${producto}" required hidden>
        </td>
        <td>
            <input class="form-control" type="number" id="${idCantidad}" name="detalles[${fila}][cantidad]" min="0" placeholder="Ingrese Cantidad" step="1" value="${cantidad}" required>
        </td>
        <td>
            <input class="form-control" type="number" id="${idUnitario}" name="detalles[${fila}][precioUnitario]" min="0" placeholder="Ingrese Cantidad" step="1" value="${precioUnitario}" required>
        </td>
        <td>
            <input class="form-control" type="text" id="${idSubtotal}" name="detalles[${fila}][subtotal]" min="0" value="${valorSubtotal}" disabled>
        </td>
        <td id="${idMsg}">
        </td>
        <input type="number" id="${idc}" name="detalles[${fila}][cant]" min="0" value="${cantidad}" hidden>
        <input id="${idv}" name="detalles[${fila}][id]" min="0" value="${id}" hidden>

    `;

    table.insertAdjacentHTML('beforeend', valorHtml);
    const codigoProducto = document.getElementById(idCodigoProducto);
    const productoObtenido = document.getElementById(idProducto);
    const cantidadObtenida = document.getElementById(idCantidad);
    const unitarioObtenido = document.getElementById(idUnitario);
    const subtotalObtenido = document.getElementById(idSubtotal);
    const msgCelda = document.getElementById(idMsg)


    // Event para código producto
    codigoProducto.addEventListener('input', function() {
        let valorCodigo = codigoProducto.value
        let nombreProducto = getProductNameById(valorCodigo);
        if (nombreProducto && nombreProducto['nombre']) {
            let nombreProductoObtenido = nombreProducto['nombre'];
            productoObtenido.value = nombreProductoObtenido;
        }else{
            productoObtenido.value = "No encontrado";
        }
        if(valorCodigo == "" || valorCodigo === null || valorCodigo === undefined){
            cantidadObtenida.value = "";
            unitarioObtenido.value = "";
            subtotalObtenido.value = "";
            cantidadObtenida.disabled = true;
        }else{
            cantidadObtenida.disabled = false;
        }


    });



    // Event para nombre de producto
    productoObtenido.addEventListener('input', function() {
        const idProducto = getProductIdByName(productoObtenido.value);
        
        codigoProducto.value = idProducto !== "ID no encontrado" ? idProducto : "";

        if (idProducto !== "ID no encontrado") {
            asignarSubtotal(idProducto, unitarioObtenido, cantidad, subtotalObtenido);
        } else {
            subtotal.value = 0;
        }
    });

    // Event para precio unidad
    unitarioObtenido.addEventListener('input', function() {
        const valorUnidad = unitarioObtenido.value;
        const cantidad = cantidadObtenida.value;
        subtotalObtenido.value = formatearValores(valorUnidad * cantidad);

        actualizarTotalVenta()

    });

    // Event para cantidad unidad
    cantidadObtenida.addEventListener('input', function() {
        var stockConseguido = getProductStockById(codigoProducto.value);
        const valorUnidad = parseInt(unitarioObtenido.value);
        const cantidad = parseInt(cantidadObtenida.value);
        if(isNaN(valorUnidad) || valorUnidad === null || valorUnidad === undefined){
            subtotalObtenido.value = 0;  
        }
        if (cantidad > stockConseguido) {
            msgCelda.innerHTML = `<p class="text-danger fw-bold">Cantidad supera el stock disponible (${stockConseguido})</p>`;
        } else {
            msgCelda.innerHTML = ""; 
        }

        let resultado = valorUnidad * cantidad;
        if(isNaN(resultado) || resultado === null || resultado === undefined){
            resultado = 0;
        }
        if (cantidad === 0 || cantidadObtenida.value === "") {
            resultado = 0;
        }
        subtotalObtenido.value = formatearValores(resultado);
        actualizarTotalVenta()
    });
    fila++;
}

let productMap = {};
let clientesMap = {};
document.addEventListener("DOMContentLoaded", function () {
    //Lista productos
    //Obtener nombre cliente para mostrar
    const clienteRut = document.getElementById('clienteRut');
    const nombreCliente = document.getElementById('clienteNombre');

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

    const nombre = getClientNameByRut(clienteRut.value);
    nombreCliente.value = nombre;
});
