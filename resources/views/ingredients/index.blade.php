@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ALERTA --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="top-bar">

        {{-- IZQUIERDA --}}
        <div class="left-actions">
            <a href="{{ route('ingredients.create') }}" class="btn-add"> Agregar</a>

            <a href="{{ route('ingredients.export') }}" class="btn-export">
                Exportar
            </a>
        </div>

        {{-- DERECHA --}}
        <form method="GET" class="right-actions">

            <select name="category" class="filter-select">
                <option value="">Todas</option>
                <option>Café y Derivados</option>
                <option>Tés e Infusiones</option>
                <option>Lácteos y Alternativas</option>
            </select>

            <input type="text" name="search" placeholder="Buscar..." class="search-input">

        </form>

    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estatus</th>
                <th>Acción</th>
            </tr>
        </thead>

        <tbody>
            @foreach($ingredients as $item)
            <tr>
                <td><input type="checkbox"></td>

                <td>{{ $item->name }}</td>

                <td>
                    <img src="{{ asset('storage/' . $item->image) }}" width="40">
                </td>

                <td>{{ $item->category }}</td>

                <td>${{ $item->cost_per_unit }}</td>

                <td>{{ $item->current_stock }} {{ $item->unit }}</td>

                <td>
                    <span class="{{ $item->status == 'activo' ? 'status-active' : 'status-inactive' }}">
                        {{ $item->status }}
                    </span>
                </td>

                <td>
                    <div class="dropdown">

                        <button class="dropdown-btn">⋮</button>

                        <div class="dropdown-content">

                            {{-- VER --}}
                            <a href="{{ route('ingredients.show', $item->id) }}" class="dropdown-item">
                                <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <span>Ver</span>
                            </a>

                            {{-- EDITAR --}}
                            <a href="{{ route('ingredients.edit', $item->id) }}" class="dropdown-item">
                                <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M16.862 3.487a2.1 2.1 0 113 3L7.5 18.75 3 21l2.25-4.5 11.612-13.013z"/>
                                </svg>
                                <span>Editar</span>
                            </a>

                            {{-- ELIMINAR --}}
                            <button type="button" onclick="confirmDelete({{ $item->id }})" class="dropdown-item delete">
    
                                <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M6 7.5h12M9 7.5v12m6-12v12M4.5 7.5h15"/>
                                </svg>

                                <span>Eliminar</span>

                            </button>
                            <form id="delete-form-{{ $item->id }}" method="POST" action="{{ route('ingredients.destroy', $item->id) }}">
                                @csrf
                                @method('DELETE')
                            </form>

                            {{-- TOGGLE STATUS --}}
                            <form method="POST" action="{{ route('ingredients.toggle', $item->id) }}">
                                @csrf
                                <button class="dropdown-item">
                                    <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M4 4v6h6M20 20v-6h-6"/>
                                    </svg>
                                    <span>Cambiar estado</span>
                                </button>
                            </form>

                        </div>
                    </div>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection

@section('styles')
    <style>
    .top-bar {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .left-actions {
        display: flex;
        gap: 10px;
    }

    .btn-add {
        background: #6B7F4E;
        color: white;
        padding: 10px 20px;
        border-radius: 20px;
        text-decoration: none;
    }

    .btn-export {
        background: #E5E7EB;
        padding: 10px 20px;
        border-radius: 20px;
        text-decoration: none;
        color: #333;
    }

    .right-actions {
        display: flex;
        gap: 10px;
    }

    .filter-select,
    .search-input {
        border-radius: 20px;
        padding: 8px 12px;
        border: 1px solid #ccc;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 15px;
        overflow: visible; 
    }

    .custom-table td,
    .custom-table th {
        padding: 12px;
    }

    .status-active {
        color: green;
    }

    .status-inactive {
        color: gray;
    }

    /* DROPDOWN */
    .dropdown {
        position: relative;
    }

    .dropdown-btn {
        background: none;
        border: none;
        cursor: pointer;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background: white;
        border-radius: 12px;
        padding: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        width: 100%;
        background: none;
        border: none;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background: #f3f4f6;
        border-radius: 8px;
    }

    .icon {
        width: 18px;
        height: 18px;
    }

    .delete {
        color: red;
    }

    /* ALERTA */
    .alert-success {
        background: #D1FAE5;
        color: #065F46;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    
    </style>
@endsection


@section('scripts')
<script>
function confirmDelete(id) {
    if (confirm("¿Estás seguro de eliminar este ingrediente? Esta acción no se puede deshacer.")) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection