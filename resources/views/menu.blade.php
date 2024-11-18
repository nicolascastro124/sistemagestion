<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
      <div class="navbar-nav">
        <ul class="navbar-nav">
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
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Productos
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('producto.listaproductos')}}">Ver Productos</a></li>
            <li><a class="dropdown-item" href="{{ route('producto.registrar')}}">Registrar Productos</a></li>

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
        <li class="nav-item">
          <a class="nav-link" href="#">Mi Cuenta</a>
        </li>
      </ul>
      </div>
  </div>
</nav>