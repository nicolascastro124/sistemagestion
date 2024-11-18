INSERT INTO Categoria (nombre) VALUES ('Frutas'),('Lácteos'),('Bebidas'),('Limpieza'),('Higiene'),('Alimentos'),('Snacks'),('Congelados'),('Vegetales'),('Conservas');
INSERT INTO MetodoPago (descripcion) VALUES ('Efectivo'), ('Tarjeta'), ('Transferencia');

-- Insert Productos
INSERT INTO Producto (Nombre, idCategoria, costo, precioVenta, stock, fechaVencimiento) VALUES 
-- Frutas (idCategoria = 1)
('Manzana Roja', 1, 300, 450, 50, '2025-03-10'),
('Plátano', 1, 250, 400, 70, '2025-04-15'),
('Naranja', 1, 280, 420, 60, '2025-03-15'),
('Limón', 1, 150, 220, 90, '2025-04-20'),
('Uva', 1, 600, 900, 40, '2025-03-25'),
('Melón', 1, 800, 1200, 30, '2025-04-10'),
('Sandía', 1, 700, 1000, 25, '2025-04-18'),
('Pera', 1, 350, 500, 55, '2025-03-22'),
('Durazno', 1, 400, 600, 65, '2025-03-12'),
('Manzana Verde', 1, 310, 460, 50, '2025-04-01'),

-- Lácteos (idCategoria = 2)
('Leche Entera', 2, 800, 1000, 40, '2025-05-15'),
('Leche Descremada', 2, 820, 1050, 35, '2025-05-20'),
('Yogur Natural', 2, 500, 750, 25, '2025-04-05'),
('Queso Cheddar', 2, 1200, 1500, 20, '2025-06-15'),
('Queso Mozzarella', 2, 1100, 1400, 22, '2025-07-10'),
('Queso Parmesano', 2, 1300, 1600, 18, '2025-06-25'),
('Mantequilla', 2, 900, 1200, 30, '2025-05-30'),
('Crema de Leche', 2, 950, 1300, 27, '2025-04-10'),
('Queso Azul', 2, 1350, 1700, 15, '2025-06-30'),
('Yogur de Frutas', 2, 550, 800, 40, '2025-07-15'),

-- Bebidas (idCategoria = 3)
('Jugo de Naranja', 3, 200, 350, 100, '2025-03-30'),
('Gaseosa', 3, 150, 300, 120, '2025-04-15'),
('Agua Mineral', 3, 100, 250, 150, '2025-05-01'),
('Vino Tinto', 3, 2000, 3000, 45, '2026-02-10'),
('Cerveza', 3, 500, 750, 80, '2025-07-15'),
('Jugo de Manzana', 3, 220, 370, 110, '2025-04-20'),
('Refresco de Cola', 3, 140, 280, 130, '2025-05-15'),
('Agua Tónica', 3, 200, 300, 70, '2025-06-10'),
('Limonada', 3, 180, 330, 90, '2025-05-25'),
('Jugo de Piña', 3, 230, 380, 60, '2025-03-20'),

-- Limpieza (idCategoria = 4)
('Detergente', 4, 900, 1300, 50, '2025-06-01'),
('Lavalozas', 4, 850, 1200, 55, '2025-06-20'),
('Desinfectante', 4, 950, 1400, 40, '2025-05-30'),
('Cloro', 4, 700, 1000, 60, '2025-07-10'),
('Limpiavidrios', 4, 600, 900, 45, '2025-04-15'),
('Limpiador Multiuso', 4, 750, 1100, 50, '2025-06-05'),
('Toallitas Desinfectantes', 4, 500, 800, 35, '2025-05-15'),
('Suavizante', 4, 800, 1200, 65, '2025-05-25'),
('Limpiahornos', 4, 950, 1300, 30, '2025-07-01'),
('Quitamanchas', 4, 1000, 1500, 25, '2025-08-01'),

-- Higiene (idCategoria = 5)
('Jabón Líquido', 5, 700, 1100, 75, '2025-07-01'),
('Shampoo', 5, 800, 1200, 70, '2025-08-01'),
('Pasta Dental', 5, 300, 500, 90, '2026-01-01'),
('Desodorante', 5, 500, 700, 85, '2025-08-15'),
('Acondicionador', 5, 600, 900, 65, '2025-06-25'),
('Crema de Manos', 5, 700, 1000, 60, '2025-07-10'),
('Enjuague Bucal', 5, 750, 1100, 40, '2025-09-01'),
('Crema Facial', 5, 1200, 1600, 35, '2025-08-25'),
('Jabón en Barra', 5, 200, 350, 110, '2025-04-05'),
('Gel de Baño', 5, 650, 950, 50, '2025-07-20'),

-- Alimentos (idCategoria = 6)
('Arroz', 6, 1000, 1500, 90, '2025-03-20'),
('Fideos', 6, 800, 1200, 95, '2025-04-10'),
('Azúcar', 6, 700, 1000, 100, '2025-06-15'),
('Aceite', 6, 1500, 2000, 80, '2025-05-05'),
('Sal', 6, 300, 450, 110, '2026-02-01'),
('Harina', 6, 600, 850, 75, '2025-04-25'),
('Café', 6, 1200, 1700, 55, '2025-07-15'),
('Té', 6, 800, 1150, 65, '2025-05-10'),
('Cereal', 6, 500, 750, 70, '2025-06-20'),
('Avena', 6, 450, 700, 85, '2025-07-05'),

-- Snacks (idCategoria = 7)
('Chips', 7, 200, 400, 60, '2025-06-05'),
('Galletas', 7, 300, 500, 75, '2025-04-25'),
('Chocolate', 7, 800, 1200, 50, '2025-07-01'),
('Barra de Cereal', 7, 250, 450, 80, '2025-05-15'),
('Palomitas', 7, 150, 300, 90, '2025-04-10'),
('Frutos Secos', 7, 1200, 1800, 40, '2025-08-01'),
('Tortillas', 7, 350, 550, 55, '2025-06-15'),
('Caramelos', 7, 100, 200, 100, '2026-01-05'),
('Chicles', 7, 50, 150, 120, '2025-12-20'),
('Mix de Frutas', 7, 500, 750, 45, '2025-05-25'),

-- Congelados (idCategoria = 8)
('Helado', 8, 1500, 2000, 40, '2025-06-25'),
('Pizza Congelada', 8, 2500, 3000, 35, '2025-05-15'),
('Empanadas Congeladas', 8, 1800, 2200, 30, '2025-07-10'),
('Pollo Congelado', 8, 3000, 3500, 20, '2025-05-30'),
('Carne de Res Congelada', 8, 3500, 4000, 25, '2025-06-01'),
('Verduras Mixtas Congeladas', 8, 1200, 1500, 60, '2025-07-25'),
('Papas Fritas Congeladas', 8, 1300, 1600, 55, '2025-05-20'),
('Frutas Congeladas', 8, 1400, 1750, 50, '2025-04-15'),
('Pescado Congelado', 8, 2800, 3300, 30, '2025-08-05'),
('Camarones Congelados', 8, 3200, 3700, 20, '2025-07-01'),

-- Vegetales (idCategoria = 9)
('Zanahoria', 9, 300, 500, 50, '2025-04-10'),
('Choclo', 9, 250, 450, 60, '2025-03-30'),
('Espinaca', 9, 200, 400, 70, '2025-04-05'),
('Brócoli', 9, 500, 700, 40, '2025-05-10'),
('Pepino', 9, 300, 500, 65, '2025-04-25'),
('Pimiento', 9, 350, 600, 55, '2025-03-15'),
('Tomate', 9, 400, 650, 80, '2025-06-20'),
('Lechuga', 9, 250, 400, 100, '2025-04-30'),
('Cebolla', 9, 450, 600, 90, '2025-07-05'),
('Ajo', 9, 600, 800, 45, '2025-08-10'),

-- Conservas (idCategoria = 10)
('Atún en Lata', 10, 800, 1200, 70, '2026-01-01'),
('Sardinas', 10, 600, 900, 80, '2025-12-15'),
('Tomate en Conserva', 10, 700, 1000, 65, '2025-11-20'),
('Frijoles en Conserva', 10, 500, 750, 90, '2025-10-10'),
('Guisantes en Conserva', 10, 550, 800, 85, '2025-09-01'),
('Maíz en Conserva', 10, 450, 700, 95, '2025-11-05'),
('Alcachofa en Conserva', 10, 750, 1000, 55, '2025-08-20'),
('Pimientos en Conserva', 10, 650, 900, 60, '2025-10-25'),
('Setas en Conserva', 10, 850, 1150, 50, '2026-02-15'),
('Chícharos en Conserva', 10, 600, 850, 75, '2026-01-10');

-- -------------------------------------------------------------

INSERT INTO Cliente (nombre, rut, telefono) VALUES ('Ana López', '10000000-4', '920642604'),
('Isabel Ruiz', '10000001-9', '963925919'),
('Renato Olivares', '10000002-1', '915315310'),
('Juan Pérez', '10000003-6', '923720231'),
('Andrés Morales', '10000004-9', '934418985'),
('Ana López', '10000005-5', '965909393'),
('Jorge García', '10000006-1', '949486965'),
('Valentina Correa', '10000007-6', '922846883'),
('Fernando Ortega', '10000008-3', '958169168'),
('Pedro Rodríguez', '10000009-4', '952793792'),
('Samuel Bravo', '10000010-7', '932541839'),
('Gabriela Ramos', '10000011-9', '928775665'),
('Isabel Ruiz', '10000012-0', '997474386'),
('Hugo Salazar', '10000013-5', '960941630'),
('Fernando Ortega', '10000014-1', '997232701'),
('Emilio Córdova', '10000016-9', '935577419'),
('Gabriela Ramos', '10000017-8', '969063605'),
('María González', '10000019-4', '919743812'),
('Sofía Castro', '10000020-0', '976235315'),
('Jorge García', '10000021-7', '943426228'),
('Gabriel Mendoza', '10000023-3', '964161353');

-- -------------------------------------------------------

INSERT INTO Venta (fecha, totalVenta, idMetodoPago, idCliente) 
VALUES 
    ('2024-11-04 10:30:00', 25000, 1, 1),
    ('2024-11-04 11:00:00', 32000, 2, 2),
    ('2024-11-04 11:30:00', 15000, 1, 3),
    ('2024-11-04 12:00:00', 45000, 3, 4),
    ('2024-11-04 12:30:00', 28000, 1, 5),
    ('2024-11-04 13:00:00', 50000, 2, 1),
    ('2024-11-04 13:30:00', 36000, 3, 2),
    ('2024-11-04 14:00:00', 41000, 1, 3),
    ('2024-11-04 14:30:00', 22000, 2, 4),
    ('2024-11-04 15:00:00', 34000, 1, 5);

INSERT INTO DetalleVenta (idVenta, idProducto, cantidad, subtotal) 
VALUES 
    (1, 2, 2, 10000), (1, 2, 1, 15000),  -- Detalles de la venta 1
    (2, 3, 1, 20000), (2, 4, 1, 12000),  -- Detalles de la venta 2
    (3, 5, 1, 15000),                    -- Detalles de la venta 3
    (4, 2, 2, 30000), (4, 3, 1, 15000),  -- Detalles de la venta 4
    (5, 7, 1, 12000), (5, 4, 1, 16000),  -- Detalles de la venta 5
    (6, 2, 2, 25000), (6, 5, 1, 25000),  -- Detalles de la venta 6
    (7, 3, 1, 20000), (7, 7, 1, 16000),  -- Detalles de la venta 7
    (8, 4, 1, 20000), (8, 5, 1, 21000),  -- Detalles de la venta 8
    (9, 10, 1, 10000), (9, 2, 2, 12000),  -- Detalles de la venta 9
    (10, 3, 1, 20000), (10, 4, 1, 14000);  -- Detalles de la venta 10

-- ------------------------------------------