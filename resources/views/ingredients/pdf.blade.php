<h1>Lista de Ingredientes</h1>

<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Precio</th>
    </tr>

    @foreach($ingredients as $item)
    <tr>
        <td>{{ $item->name }}</td>
        <td>{{ $item->category }}</td>
        <td>${{ $item->price }}</td>
    </tr>
    @endforeach
</table>