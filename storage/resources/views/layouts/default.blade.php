<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>
    <!-- Aquí puedes incluir tus estilos o scripts globales -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- assets compilados por Vite -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body>
    <header>
        <!-- Contenido del header -->
        @yield('header')
    </header>

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
