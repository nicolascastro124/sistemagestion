<h1>Editar Producto</h1>

<form action="{{ route('producto.actualizar', $producto['ID_Producto']) }}" method="POST">
    @csrf
    <div>
        <label for="Nombre">Nombre:</label>
        <input type="text" id="Nombre" name="Nombre" value="{{ $producto['Nombre'] }}" required>
    </div>

    <div>
        <label for="Costo">Costo:</label>
        <input type="number" id="Costo" name="Costo" value="{{ $producto['Costo'] }}" required>
    </div>

    <div>
        <label for="PrecioVenta">Precio de Venta:</label>
        <input type="number" id="PrecioVenta" name="PrecioVenta" value="{{ $producto['PrecioVenta'] }}" required>
    </div>

    <div>
        <label for="Stock">Stock:</label>
        <input type="number" id="Stock" name="Stock" value="{{ $producto['Stock'] }}" required>
    </div>

    <div>
        <label for="FechaVencimiento">Fecha de Vencimiento:</label>
        <input type="date" id="FechaVencimiento" name="FechaVencimiento" value="{{ $producto['FechaVencimiento'] }}">
    </div>

    <div>
        <label for="ID_Categoria">Categoría:</label>
        <select id="ID_Categoria" name="ID_Categoria" required>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria['ID_Categoria'] }}" {{ $producto['ID_Categoria'] == $categoria['ID_Categoria'] ? 'selected' : '' }}>
                    {{ $categoria['nombre'] }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit">Guardar Cambios</button>
</form>

<a href="{{ route('producto.listaproductos') }}">Cancelar</a>
