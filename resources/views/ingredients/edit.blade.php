@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="title">Editar Ingrediente</h2>

    <form action="{{ route('ingredients.update', $ingredient->id) }}" method="POST" enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="name" value="{{ $ingredient->name }}" required>
        </div>

        <div class="form-group">
            <label>Categoría</label>
            <select name="category" required>
                <option {{ $ingredient->category == 'Café y Derivados' ? 'selected' : '' }}>Café y Derivados</option>
                <option {{ $ingredient->category == 'Tés e Infusiones' ? 'selected' : '' }}>Tés e Infusiones</option>
                <option {{ $ingredient->category == 'Lácteos y Alternativas' ? 'selected' : '' }}>Lácteos y Alternativas</option>
                <option {{ $ingredient->category == 'Panadería y Repostería' ? 'selected' : '' }}>Panadería y Repostería</option>
            </select>
        </div>

        <div class="form-group">
            <label>Precio</label>
            <input type="number" name="price" step="0.01" value="{{ $ingredient->cost_per_unit }}" required>
        </div>

        <div class="form-group">
            <label>Imagen actual</label><br>
            <img src="{{ asset('storage/' . $ingredient->image) }}" width="80">
        </div>

        <div class="form-group">
            <label>Cambiar imagen</label>
            <input type="file" name="image">
        </div>

        <div class="form-group">
            <label>Estatus</label>
            <select name="status">
                <option value="activo" {{ $ingredient->status == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ $ingredient->status == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <div class="form-group">
            <label>Unidad</label>
            <input type="text" name="unit" value="{{ $ingredient->unit }}">
        </div>

        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" value="{{ $ingredient->current_stock }}">
        </div>

        <button type="submit" class="btn-save">Actualizar</button>

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