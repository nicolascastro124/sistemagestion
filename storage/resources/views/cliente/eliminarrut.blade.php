
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Cliente</title>
</head>
<body>
<form action="/cliente/eliminar/rut" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este cliente?');">
    @csrf <!-- Token CSRF -->
    @method('DELETE') 

    <label for="id">Rut del Cliente:</label>
    <input type="number" name="id" id="id" required> <!-- Campo de entrada para el ID -->

    <button type="submit">Eliminar</button>
</form>

</body>
</html>