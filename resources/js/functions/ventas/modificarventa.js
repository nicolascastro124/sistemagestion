// resources/js/functions/modificarventa.js



// Devuelve Nombre de producto
export function getProductNameById(productId) {
    return productMap[productId] || "Producto no encontrado";
}
//Obtener nombre de producto
export function getNombreProducto(idProducto) {
    const producto = productos.find(p => p.id === idProducto);
    return producto ? producto.nombre : "Producto no encontrado";
}
//Obtener Id de producto
export function getProductIdByName(productName) {
    for (const [id, product] of Object.entries(productMap)) {
        if (product.nombre === productName) {
            return id; // Devuelve el ID si el nombre coincide
        }
    }
    return "ID no encontrado"; // Retorna este mensaje si no se encuentra el producto
}

//Obtener valor de producto
function getProductValueById(productId) {
    return productMap[productId] ? productMap[productId].precioVenta : 0;
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

//Actualiza valor total para vista
export function actualizarTotalVenta() {
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

//Pasa los valores con formato a sin formato ($100.000 => 100000)
function quitarFormato(valorFormateado) {
    let valorSinFormato = valorFormateado.replace(/\$|\.|,/g, "");

    // Convertimos el valor a un número entero
    return parseInt(valorSinFormato);
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

// Devuelve stock de producto
export function getProductStockById(productId) {
    return productMap[productId] ? productMap[productId].stock : 0;
}

// Asignación de subtotal específica para cada fila
export function asignarSubtotal(idProducto,unitario, cantidad, subtotal) {
    const precioVenta = getProductValueById(idProducto);
    unitario.value = precioVenta;
    const cantidadIngresada = parseFloat(cantidad.value) || 0;
    subtotal.value = formatearValores(precioVenta * cantidadIngresada);
    
}

//Corregir errores al eliminar
function reasignarIndices() {
    const filas = document.querySelectorAll('#detalleTable tbody tr');
    filas.forEach((fila, index) => {
        const inputs = fila.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/\[\d+\]/, `[${index}]`);
                input.setAttribute('name', newName);
            }
        });
    });
}
