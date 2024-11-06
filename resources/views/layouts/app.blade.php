<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>
    <!--estilos / scripts -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- assets compilados por Vite -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <br>
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
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
