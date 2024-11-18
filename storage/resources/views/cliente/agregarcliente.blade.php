<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cliente</title>
</head>
<body>
    <form action="/cliente/agregar" method="POST">
        @csrf <!-- Laravel CSRF token para la protección contra CSRF -->
        <label for="rut">RUT:</label>
        <input type="text" name="rut" id="rut" required>
        <br>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <br>
        <label for="telefono">Telefono:</label>
        <input type="text" name="telefono" id="telefono" required>
        <br>
        <button type="submit">Agregar Cliente</button>
    </form>
</body>
</html>
