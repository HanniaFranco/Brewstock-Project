@extends('layouts.app')

@section('title', 'Ingredientes')
@section('page_title', 'Ingredientes')

@section('content')
<div class="container py-4">

    <div class="grid-categories">
        @php
            $categories = [
                ['name' => 'Café y Derivados', 'icon' => 'cafe.png'],
                ['name' => 'Tés e Infusiones', 'icon' => 'te.png'],
                ['name' => 'Lácteos y Alternativas', 'icon' => 'leche.png'],
                ['name' => 'Panadería y Repostería', 'icon' => 'pan.png'],
                ['name' => 'Snacks', 'icon' => 'snack.png'],
                ['name' => 'Bebidas Frías', 'icon' => 'bebida.png'],
                ['name' => 'Ingredientes Básicos', 'icon' => 'ingredientes.png'],
                ['name' => 'Desechables y Empaques', 'icon' => 'empaques.png'],
                ['name' => 'Limpieza e Insumos', 'icon' => 'limpieza.png'],
            ];
        @endphp

        @foreach ($categories as $category)
            <div class="category-card">
                <a href="{{ route('ingredients.index', ['category' => $category['name']]) }}" class="category-card">
                <img src="{{ asset('assets/' . $category['icon']) }}" alt="{{ $category['name'] }}">
                <p>{{ $category['name'] }}</p>
                </a>
            </div>
            
        @endforeach
    </div>

</div>
@endsection

@section('styles')
    <style>
        .grid-categories {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .category-card {
            background-color: #E1EBBE;
            border-radius: 15px;
            text-align: center;
            padding: 20px;
            transition: 0.2s;
            cursor: pointer;
        }

        .category-card:hover {
            transform: scale(1.05);
        }

        .category-card img {
            width: 60px;
            margin-bottom: 10px;
        }

        .category-card p {
            font-weight: 600;
            color: #4A5A3A;
        }

        .category-card {
        text-decoration: none;
        list-style: none;
        border: none;
        outline: none;
    }

    .category-card:focus,
    .category-card:active {
        outline: none;
        border: none;
    }

    .category-card p {
        margin: 0;
    }

    .category-card {
        display: block;
        color: inherit;
    }
    </style>
@endsection