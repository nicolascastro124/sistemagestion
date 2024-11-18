// js/registrarventa.js

let fila = 0;


//Limpieza campos
window.onload = function() {
    document.getElementById("totalVenta").value = ""; 
};

// Devuelve Nombre de producto
export function getProductNameById(productId) {
    return productMap[productId] ? productMap[productId].nombre : "Producto no encontrado";
}

// Devuelve Valor de producto
export function getProductValueById(productId) {
    return productMap[productId] ? productMap[productId].precioVenta : 0;
}

// Devuelve stock de producto
export function getProductStockById(productId) {
    return productMap[productId] ? productMap[productId].stock : 0;
}
// Devuelve ID de producto
function getProductIdByName(productName) {
    for (const [id, product] of Object.entries(productMap)) {
        if (product.nombre === productName) {
            return id; // Devuelve el ID si el nombre coincide
        }
    }
    return "ID no encontrado"; // Retorna este mensaje si no se encuentra el producto
}

//Funcion para nueva fila
export function agregarFilaDetalle() {
    const detalleTable = document.getElementById('detalleTable').getElementsByTagName('tbody')[0];
    const nuevaFila = document.createElement('tr');
    let valorHtml = '';

    // Asigna IDs únicos a cada campo de entrada
    const idCodigoProducto = `codigoProducto_${fila}`;
    const idProducto = `producto_${fila}`;
    const idCantidad = `cantidad_${fila}`;
    const idUnitario = `precioUnitario_${fila}`
    const idSubtotal = `subtotal_${fila}`;
    const idMsg = `msg_${fila}`;
    // Crea las celdas con los elementos de entrada
    valorHtml =  `
        <td>
            <input class="form-control" id="${idCodigoProducto}" name="detalles[${fila}][codigo_producto]" placeholder="Ingrese Codigo">
        </td>
        <td>
            <input class="form-control" id="${idProducto}" name="detalles[${fila}][producto]" list="productList" placeholder="Ingrese Producto">
        </td>
        <td>
        
            <input class="form-control" type="number" id="${idCantidad}" name="detalles[${fila}][cantidad]" min="0" placeholder="Ingrese Cantidad" step="1" disabled>
        </td>
        <td>
            <input class="form-control" type="text" id="${idUnitario}" name="detalles[${fila}][precioUnitario]" min="0" disabled>
        </td>
        <td>
            <input class="form-control" type="text" id="${idSubtotal}" name="detalles[${fila}][subtotal]" min="0" disabled>
        </td>
        <td id="${idMsg}">
        </td>
    `;
    if(fila > 0){
        valorHtml += `
        <td>
            <button type="button" class="btn btn-danger btn-sm rounded-pill" onclick="eliminaFila(this)"><i class="fa-solid fa-minus"></i></button>
        </td>
    `;
    }
    nuevaFila.innerHTML = valorHtml;

    // Agrega la nueva fila al cuerpo de la tabla
    detalleTable.appendChild(nuevaFila);

    const codigoProducto = document.getElementById(idCodigoProducto);
    const producto = document.getElementById(idProducto);
    const cantidad = document.getElementById(idCantidad);
    const unitario = document.getElementById(idUnitario);
    const subtotal = document.getElementById(idSubtotal);

    // Event para código producto
    codigoProducto.addEventListener('input', function() {
        const nombreProducto = getProductNameById(codigoProducto.value);
        producto.value = nombreProducto !== "Producto no encontrado" ? nombreProducto : "";
        verificarCampos(codigoProducto, producto, cantidad, subtotal);

        if (nombreProducto !== "Producto no encontrado") {
            asignarSubtotal(codigoProducto.value, unitario, cantidad, subtotal);
        } else {
            subtotal.value = 0;
        }
    });

    // Event para nombre de producto
    producto.addEventListener('input', function() {
        const idProducto = getProductIdByName(producto.value);
        codigoProducto.value = idProducto !== "ID no encontrado" ? idProducto : "";
        verificarCampos(codigoProducto, producto, cantidad, subtotal);

        if (idProducto !== "ID no encontrado") {
            asignarSubtotal(idProducto, unitario, cantidad, subtotal);
        } else {
            subtotal.value = 0;
        }
    });

    // Event para cantidad
    cantidad.addEventListener('input', function() {
        const cantidadVendida = cantidad.value;
        const stockProducto = getProductStockById(codigoProducto.value);
        const filaActual = cantidad.closest('tr').rowIndex - 2;
        const idMsg = `msg_${filaActual}`;
        const msgCelda = document.getElementById(idMsg)

        if (cantidadVendida > stockProducto) {
            msgCelda.innerHTML = `<p class="text-danger fw-bold">Cantidad supera el stock disponible (${stockProducto})</p>`;
        } else {
            msgCelda.innerHTML = ""; 
        }

        asignarSubtotal(codigoProducto.value, unitario, cantidad, subtotal);
        actualizarTotalVenta();
    });
    fila++;
}


// Verificación de campos específica para cada fila
function verificarCampos(codigo, producto, cantidad, subtotal) {
    if (codigo.value && producto.value) {
        cantidad.disabled = false;
    } else {
        cantidad.value = 0;
        subtotal.value = 0;
        cantidad.disabled = true;
    }
}

// Asignación de subtotal específica para cada fila
export function asignarSubtotal(idProducto,unitario, cantidad, subtotal) {
    const precioVenta = getProductValueById(idProducto);
    // console.log(unitario)
    unitario.value = formatearValores(precioVenta);
    const cantidadIngresada = parseFloat(cantidad.value) || 0;
    subtotal.value = formatearValores(precioVenta * cantidadIngresada);
    
}

//Actualiza valor de total venta
function actualizarTotalVenta() {
    const subtotalElements = document.querySelectorAll('[id^="subtotal_"]'); // Selecciona todos los inputs de subtotal
    let total = 0;
    subtotalElements.forEach(element => {
        const subtotalValue = parseFloat(quitarFormato(element.value)) || 0;
        total += subtotalValue;
    });

    // Actualiza el campo Total Venta
    const totalVentaElement = document.getElementById('totalVenta');
    if (totalVentaElement) {
        totalVentaElement.value = formatearValores(total);
    }
}

//Devuelve valor en formato $xxx.xxx (utilizado en Chile)
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

//Quita el formato CLP
function quitarFormato(valorFormateado) {
    let valorSinFormato = valorFormateado.replace(/\$|\.|,/g, "");

    // Convertimos el valor a un número entero
    return parseInt(valorSinFormato);
}

//Elimina fila
export function eliminaFila(boton) {
    const detalleTableBody = document.getElementById('detalleTable').getElementsByTagName('tbody')[0];
    // Verificar que haya más de una fila antes de eliminar
    if (detalleTableBody.rows.length > 2) {
        const fila = boton.closest('tr'); 
        fila.remove(); 
        actualizarTotalVenta(); // Actualiza el total de la venta 

    }

}

document.getElementById('registroVenta').addEventListener('submit', function(event) {
    if (!validarFormulario()) {
        event.preventDefault(); // Evita el envío del formulario 
        Swal.fire({
            title: 'No es posible registrar venta',
            text: "Hay errores en la cantidad ingresada. Verifique que la cantidad no supere el stock disponible.",
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Entendido'
        });
    }
});

//Validar si existen mensajes de errores
function validarFormulario() {
    const mensajesError = document.querySelectorAll('td[id^="msg_"] p.text-danger');
    return mensajesError.length === 0; // Si no hay mensajes de error, retorna true, permitiendo el envío
}

// Devuelve Nombre de Cliente buscando por rut
export function getClientNameByRut(rut) {
    return clientesMap[rut] ? clientesMap[rut].nombre : "No encontrado";
}


//Obtener nombre cliente para mostrar
const clienteRut = document.getElementById('clienteRut');
const nombreCliente = document.getElementById('clienteNombre');
clienteRut.addEventListener('input', function() {
    const nombre = getClientNameByRut(clienteRut.value);
    nombreCliente.value = nombre;
});