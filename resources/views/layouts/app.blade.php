<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>

    <!-- Librerías externas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Assets compilados por Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <header>
        <!-- Contenido del header -->
        @yield('header')
    </header>

    @include('menu')

    <center>
        <p class="h1">@yield('maintitle', 'Mi Aplicación')</p>
    </center>

    <main>
        @yield('content')

    </main>

    <footer>
        <!-- Contenido del footer -->
    </footer>
    
</body>
</html>
