@extends('layouts.app')

@section('title', 'Categorías de Inventario')
@section('page_title', 'Categorías de Inventario')

@section('styles')
    <style>
        .page-header {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            border: 2px solid #8fbc8f;
        }

        .page-title {
            color: #5a7248;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .category-card {
            background: white;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #8fbc8f;
            text-decoration: none;
            color: inherit;
        }

        .category-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #5a7248 0%, #8fbc8f 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
        }

        .category-name {
            font-size: 18px;
            font-weight: 600;
            color: #5a7248;
            margin: 0;
            line-height: 1.3;
        }

        .category-count {
            font-size: 14px;
            color: #666;
            margin-top: 8px;
        }

        @media (max-width: 768px) {
            .categories-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }

            .category-card {
                padding: 20px 15px;
            }

            .category-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }

            .category-name {
                font-size: 16px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Categorías de Inventario</h1>
    </div>

    <!-- Categories Grid -->
    <div class="categories-grid">
        <!-- Café y Derivados -->
        <a href="#" class="category-card" onclick="handleCategoryClick('cafe-derivados')">
            <div class="category-icon">
                <i class="fas fa-coffee"></i>
            </div>
            <h3 class="category-name">Café y Derivados</h3>
            <p class="category-count">12 productos</p>
        </a>

        <!-- Tés e Infusiones -->
        <a href="#" class="category-card" onclick="handleCategoryClick('tes-infusiones')">
            <div class="category-icon">
                <i class="fas fa-leaf"></i>
            </div>
            <h3 class="category-name">Tés e Infusiones</h3>
            <p class="category-count">8 productos</p>
        </a>

        <!-- Lácteos y Alternativas -->
        <a href="#" class="category-card" onclick="handleCategoryClick('lacteos-alternativas')">
            <div class="category-icon">
                <i class="fas fa-glass-whiskey"></i>
            </div>
            <h3 class="category-name">Lácteos y Alternativas</h3>
            <p class="category-count">15 productos</p>
        </a>

        <!-- Panadería y Repostería -->
        <a href="#" class="category-card" onclick="handleCategoryClick('panaderia-reposteria')">
            <div class="category-icon">
                <i class="fas fa-bread-slice"></i>
            </div>
            <h3 class="category-name">Panadería y Repostería</h3>
            <p class="category-count">20 productos</p>
        </a>

        <!-- Snacks -->
        <a href="#" class="category-card" onclick="handleCategoryClick('snacks')">
            <div class="category-icon">
                <i class="fas fa-cookie"></i>
            </div>
            <h3 class="category-name">Snacks</h3>
            <p class="category-count">18 productos</p>
        </a>

        <!-- Bebidas Frías -->
        <a href="#" class="category-card" onclick="handleCategoryClick('bebidas-frias')">
            <div class="category-icon">
                <i class="fas fa-blender"></i>
            </div>
            <h3 class="category-name">Bebidas Frías</h3>
            <p class="category-count">10 productos</p>
        </a>

        <!-- Ingredientes Básicos -->
        <a href="#" class="category-card" onclick="handleCategoryClick('ingredientes-basicos')">
            <div class="category-icon">
                <i class="fas fa-pepper-hot"></i>
            </div>
            <h3 class="category-name">Ingredientes Básicos</h3>
            <p class="category-count">25 productos</p>
        </a>

        <!-- Desechables y Empaques -->
        <a href="#" class="category-card" onclick="handleCategoryClick('desechables-empaques')">
            <div class="category-icon">
                <i class="fas fa-box"></i>
            </div>
            <h3 class="category-name">Desechables y Empaques</h3>
            <p class="category-count">14 productos</p>
        </a>

        <!-- Limpieza e Insumos -->
        <a href="#" class="category-card" onclick="handleCategoryClick('limpieza-insumos')">
            <div class="category-icon">
                <i class="fas fa-spray-can"></i>
            </div>
            <h3 class="category-name">Limpieza e Insumos</h3>
            <p class="category-count">9 productos</p>
        </a>
    </div>

    <script>
        function handleCategoryClick(categorySlug) {
            // Redirigir a la página de ingredientes filtrada por categoría
            window.location.href = `{{ route('inventory.ingredients') }}?category=${categorySlug}`;
        }
    </script>
@endsection
