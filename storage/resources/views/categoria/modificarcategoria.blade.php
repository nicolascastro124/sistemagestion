<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Categoria</title>
</head>
<body>
<form action="/categoria/modificar/{id}" method="POST">
    @csrf <!-- Token CSRF -->
    @method('PUT') <!-- Método HTTP PUT para actualización -->

    <label for="nombre">Nuevo Nombre de la Categoría:</label>
    <input type="text" id="nombre" name="nombre" required maxlength="100">

    <!-- Botón para enviar el formulario -->
    <button type="submit">Actualizar Categoría</button>
</form>

</body>
</html>
