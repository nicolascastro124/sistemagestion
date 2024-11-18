<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Categoria</title>
</head>
<body>
    <form action="/categoria/agregar" method="POST">
        @csrf <!-- Laravel CSRF token para la protección contra CSRF -->
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <button type="submit">Agregar Categoria</button>
    </form>
</body>
</html>
