<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <div class="navbar-nav w-100">
      <!-- Sección izquierda -->
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/"><i class="fa-solid fa-house"></i></a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Ventas
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('venta.lista')}}">Ver Ventas</a></li>
            <li><a class="dropdown-item" href="{{ route('venta.registrar')}}">Registrar Ventas</a></li>
            <li><a class="dropdown-item" href="{{ route('venta.nuevometodo')}}">Nuevo Metodo Pago</a></li>

          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Productos
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('producto.listaproductos')}}">Ver Productos</a></li>
            <li><a class="dropdown-item" href="{{ route('producto.registrar')}}">Registrar Productos</a></li>
            <li><a class="dropdown-item" href="{{ route('producto.nuevacategoria')}}">Nueva Categoria Producto</a></li>

          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Clientes
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('cliente.listaclientes') }}">Ver Clientes</a></li>
            <li><a class="dropdown-item" href="{{ route('cliente.registrar') }}">Registrar Cliente</a></li>
          </ul>
        </li>
      </ul>
      <!-- Sección derecha -->
      <ul class="navbar-nav ms-auto">
        <span class="navbar-text fw-bold">
          {{ auth()->user()->name }}
        </span>
        @if(auth()->user()->is_admin)
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Informes
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('info.ventasfecha')}}">Ventas por rango fechas</a></li>
            <li><a class="dropdown-item" href="{{ route('info.categoriaproductos')}}">Categorias por rango fechas</a></li>
            <li><a class="dropdown-item" href="{{ route('info.rentabilidadproductos')}}">Rentabilidad Productos</a></li>

          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Usuarios
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('users')}} ">Ver Usuarios</a></li>
            <li><a class="dropdown-item" href="{{ route('register')}}">Registrar Usuario</a></li>
          </ul>
        </li>
        @endif
        <li class="nav-item">
          <a class="nav-link" href="#" id="logoutNav">Cerrar Sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
  @csrf
</form>

<script>
  document.getElementById('logoutNav').addEventListener('click', function(e) {
    e.preventDefault(); 
    document.getElementById('logoutForm').submit();
  });
</script>

