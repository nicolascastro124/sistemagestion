INSERT INTO Venta (Fecha, TotalVenta, MetodoPago, ID_Cliente) 
VALUES 
    ('2024-11-04 10:30:00', 25000, 'Efectivo', 1),
    ('2024-11-04 11:00:00', 32000, 'Tarjeta', 2),
    ('2024-11-04 11:30:00', 15000, 'Efectivo', 3),
    ('2024-11-04 12:00:00', 45000, 'Transferencia', 4),
    ('2024-11-04 12:30:00', 28000, 'Efectivo', 5),
    ('2024-11-04 13:00:00', 50000, 'Tarjeta', 1),
    ('2024-11-04 13:30:00', 36000, 'Transferencia', 2),
    ('2024-11-04 14:00:00', 41000, 'Efectivo', 3),
    ('2024-11-04 14:30:00', 22000, 'Tarjeta', 4),
    ('2024-11-04 15:00:00', 34000, 'Efectivo', 5);

INSERT INTO DetalleVenta (ID_Venta, ID_Producto, Cantidad, Subtotal) 
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
