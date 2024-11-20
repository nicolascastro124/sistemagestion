<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login')</title>

    <!-- Librerías externas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Assets compilados por Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<center>
    <p class="h1">Iniciar Sesión</p>

    <form action="{{ route('login') }}" name="conexion" id="conexion" method="POST">
        @csrf
        <table class="table-light border">
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-2">
                    Rut
                </td>
                <td class="table-light border mx-auto p-1">
                    <input class="form-control form-control-sm" type="text" name="rut" id="rut" value="{{ old('rut') }}" required>
                </td>
            </tr>
            <tr class="table-light border bg-body-tertiary">
                <td class="table-light border mx-auto p-2">
                    Contraseña
                </td>
                <td class="table-light border mx-auto p-1">
                    <input class="form-control form-control-sm" type="password" name="password" id="password" required>
                </td>
            </tr>
            <tr class="table-light border ">
                <td class="table-light border"></td>
                <td class="table-light border mx-auto p-1">

                </td>
            </tr>
            <tr class="table-light border">
                <td class="table-light border">
                    <button class="btn btn-light btn-sm" type="submit">Ingresar</button>
                </td>
                <td class="table-light border">
                    <input class="btn btn-light btn-sm float-end" name="reset" type="reset" value="Borrar">
                </td>
            </tr>
        </table>
    </form>
<center>

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</body>
</html>
