@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card-show">

        <img src="{{ asset('storage/' . $ingredient->image) }}" class="img-show">

        <h2>{{ $ingredient->name }}</h2>

        <p><strong>Categoría:</strong> {{ $ingredient->category }}</p>
        <p><strong>Precio:</strong> ${{ $ingredient->cost_per_unit }}</p>
        <p><strong>Stock:</strong> {{ $ingredient->current_stock }} {{ $ingredient->unit }}</p>

        <span class="{{ $ingredient->status == 'activo' ? 'status-active' : 'status-inactive' }}">
            {{ $ingredient->status }}
        </span>

        <br><br>
        <a href="{{ route('ingredients.index') }}" class="btn-add">Volver</a>

    </div>

</div>
@endsection

@section('styles')
    <style>
    .card-show {
        background: white;
        padding: 30px;
        border-radius: 20px;
        max-width: 400px;
    }

    .img-show {
        width: 100%;
        border-radius: 15px;
        margin-bottom: 15px;
    }
    </style>
@endsection