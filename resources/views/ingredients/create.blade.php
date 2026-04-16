@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="title">Nuevo Ingrediente</h2>

    <form action="{{ route('ingredients.store') }}" method="POST" enctype="multipart/form-data" class="form-card">
        @csrf

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Categoría</label>
            <select name="category" required>
                <option>Café y Derivados</option>
                <option>Tés e Infusiones</option>
                <option>Lácteos y Alternativas</option>
                <option>Panadería y Repostería</option>
                <option>Snacks</option>
                <option>Bebidas Frías</option>
                <option>Ingredientes Básicos</option>
                <option>Desechables y Empaques</option>
                <option>Limpieza e Insumos</option>
            </select>
        </div>

        <div class="form-group">
            <label>Precio</label>
            <input type="number" name="price" step="0.01" required>
        </div>

        <div class="form-group">
            <label>Imagen</label>
            <input type="file" name="image" required>
        </div>

        <div class="form-group">
            <label>Estatus</label>
            <select name="status">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>

        <div class="form-group">
            <label>Unidad</label>
            <input type="text" name="unit" placeholder="kg, litros, piezas">
        </div>

        <div class="form-group">
            <label>Stock inicial</label>
            <input type="number" name="stock">
        </div>

        <button type="submit" class="btn-save">Guardar</button>

    </form>

</div>
@endsection


@section('styles')
    <style>
    .title {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .form-card {
        background: white;
        padding: 30px;
        border-radius: 20px;
        max-width: 500px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #555;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #ddd;
    }

    .btn-save {
        background: #6B7F4E;
        color: white;
        padding: 12px;
        border-radius: 20px;
        border: none;
        width: 100%;
    }
    </style>
@endsection