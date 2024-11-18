CREATE DATABASE IF NOT EXISTS sistemagestion;
USE sistemagestion;
-- Tabla Categoria (Creada luego)
CREATE TABLE Categoria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla Producto
CREATE TABLE Producto (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    costo INTEGER NOT NULL,
    precioVenta INTEGER NOT NULL,
    stock INT NOT NULL,
    fechaVencimiento DATE NULL,
    idCategoria INT NOT NULL,
    activo INT NOT NULL,
    CONSTRAINT FK_Producto_Categoria FOREIGN KEY (idCategoria) REFERENCES Categoria(id)
);

-- Tabla Cliente
CREATE TABLE Cliente (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    rut VARCHAR(15) NOT NULL,
    telefono VARCHAR(15) NOT NULL
);


-- Tabla Proveedor
CREATE TABLE Proveedor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(15) NOT NULL
);

-- Tabla Factura
CREATE TABLE Factura (
    id INT PRIMARY KEY AUTO_INCREMENT,
    Fecha DATE NOT NULL,
    Total INTEGER NOT NULL,
    idProveedor INT NOT NULL,
    CONSTRAINT FK_Factura_Proveedor FOREIGN KEY (idProveedor) REFERENCES Proveedor(id)
);



-- Tabla Metodo Pago (Creada luego)
CREATE TABLE MetodoPago (
    id INT PRIMARY KEY AUTO_INCREMENT,
    descripcion VARCHAR(50) NOT NULL
);

-- Tabla Detalle_Factura
CREATE TABLE Detalle_Factura (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idFactura INT NOT NULL,
    idProducto INT NOT NULL,
    cantidad INT NOT NULL,
    precioCompra INTEGER NOT NULL,
    CONSTRAINT FK_DetalleFactura_Factura FOREIGN KEY (idFactura) REFERENCES Factura(id),
    CONSTRAINT FK_DetalleFactura_Producto FOREIGN KEY (idProducto) REFERENCES Producto(id)
);

-- Tabla Venta
CREATE TABLE Venta (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha DATETIME NOT NULL,
    totalVenta INTEGER NOT NULL,
    idMetodoPago INT NOT NULL,
    idCliente INT NULL,
    CONSTRAINT FK_Venta_Cliente FOREIGN KEY (idCliente) REFERENCES Cliente(id),
    CONSTRAINT FK_Venta_MetodoPago FOREIGN KEY (idMetodoPago) REFERENCES MetodoPago(id)
);

-- Tabla DetalleVenta
CREATE TABLE DetalleVenta (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idVenta INT NOT NULL,
    idProducto INT NOT NULL,
    Cantidad INT NOT NULL,
    Subtotal INTEGER NOT NULL,
    CONSTRAINT FK_DetalleVenta_Venta FOREIGN KEY (idVenta) REFERENCES Venta(id),
    CONSTRAINT FK_DetalleVenta_Producto FOREIGN KEY (idProducto) REFERENCES Producto(id)
);
