-- Tabla Producto
CREATE TABLE Producto (
    ID_Producto INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100) NOT NULL,
    Categoria VARCHAR(50) NOT NULL,
    Costo INTEGER NOT NULL,
    PrecioVenta INTEGER NOT NULL,
    Stock INT NOT NULL,
    FechaVencimiento DATE NULL
);

-- Tabla Cliente
CREATE TABLE Cliente (
    ID_Cliente INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100) NOT NULL,
    Rut VARCHAR(15) NOT NULL,
    Telefono VARCHAR(15) NOT NULL
);

-- Tabla Venta
CREATE TABLE Venta (
    ID_Venta INT PRIMARY KEY AUTO_INCREMENT,
    Fecha DATETIME NOT NULL,
    TotalVenta INTEGER NOT NULL,
    MetodoPago VARCHAR(30) NOT NULL,
    ID_Cliente INT NULL,
    CONSTRAINT FK_Venta_Cliente FOREIGN KEY (ID_Cliente) REFERENCES Cliente(ID_Cliente)
);

-- Tabla DetalleVenta
CREATE TABLE DetalleVenta (
    ID_Detalle INT PRIMARY KEY AUTO_INCREMENT,
    ID_Venta INT NOT NULL,
    ID_Producto INT NOT NULL,
    Cantidad INT NOT NULL,
    Subtotal INTEGER NOT NULL,
    CONSTRAINT FK_DetalleVenta_Venta FOREIGN KEY (ID_Venta) REFERENCES Venta(ID_Venta),
    CONSTRAINT FK_DetalleVenta_Producto FOREIGN KEY (ID_Producto) REFERENCES Producto(ID_Producto)
);

-- Tabla Proveedor
CREATE TABLE Proveedor (
    ID_Proveedor INT PRIMARY KEY AUTO_INCREMENT,
    Nombre VARCHAR(100) NOT NULL,
    Telefono VARCHAR(15) NOT NULL
);

-- Tabla Factura
CREATE TABLE Factura (
    ID_Factura INT PRIMARY KEY AUTO_INCREMENT,
    ID_Proveedor INT NOT NULL,
    Fecha DATE NOT NULL,
    Total INTEGER NOT NULL,
    CONSTRAINT FK_Factura_Proveedor FOREIGN KEY (ID_Proveedor) REFERENCES Proveedor(ID_Proveedor)
);

-- Tabla Detalle_Factura
CREATE TABLE Detalle_Factura (
    ID_Detalle INT PRIMARY KEY AUTO_INCREMENT,
    ID_Factura INT NOT NULL,
    ID_Producto INT NOT NULL,
    Cantidad INT NOT NULL,
    Precio_Compra INTEGER NOT NULL,
    CONSTRAINT FK_DetalleFactura_Factura FOREIGN KEY (ID_Factura) REFERENCES Factura(ID_Factura),
    CONSTRAINT FK_DetalleFactura_Producto FOREIGN KEY (ID_Producto) REFERENCES Producto(ID_Producto)
);
