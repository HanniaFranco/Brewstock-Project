@extends('layouts.app')

@section('title', 'Categorías')
@section('page_title', 'Categorías de Productos')

@section('styles')
    <style>
        .filter-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 20px;
            border: 2px solid #5a7248;
            background: white;
            color: #5a7248;
            border-radius: 25px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: #5a7248;
            color: white;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .category-card {
            background: #e8f2e8;
            border: 1px solid #c8d7c8;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .category-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(90, 114, 72, 0.15);
            background: #d9ead9;
        }

        .category-icon {
            font-size: 50px;
            margin-bottom: 15px;
            color: #5a7248;
        }

        .category-name {
            font-size: 16px;
            font-weight: 600;
            color: #5a7248;
            margin: 0;
        }

        .category-card-link {
            text-decoration: none;
            color: inherit;
        }

        .category-card-link:hover .category-card {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(90, 114, 72, 0.15);
            background: #d9ead9;
        }

        @media (max-width: 768px) {
            .categories-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 15px;
            }
            
            .filter-buttons {
                justify-content: center;
            }
            
            .category-card {
                padding: 20px 15px;
                min-height: 120px;
            }
            
            .category-icon {
                font-size: 40px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Filter Buttons -->
    <div class="filter-buttons">
        <button class="filter-btn active" onclick="filterByCategory('all')">Todas</button>
        <button class="filter-btn" onclick="filterByCategory('hot')">Bebidas calientes</button>
        <button class="filter-btn" onclick="filterByCategory('tea')">Tés e Infusiones</button>
        <button class="filter-btn" onclick="filterByCategory('cold')">Bebidas frías</button>
    </div>

    <!-- Categories Grid -->
    <div class="categories-grid">
        @foreach($categories as $category)
        <a href="{{ route('products.category', $category['id']) }}" class="category-card-link">
            <div class="category-card" data-category="{{ $category['filter_group'] }}">
                @switch($category['id'])
                    @case('hot')
                        <i class="fas fa-mug-hot category-icon"></i>
                        @break
                    @case('tea')
                        <i class="fas fa-leaf category-icon"></i>
                        @break
                    @case('bakery')
                        <i class="fas fa-birthday-cake category-icon"></i>
                        @break
                    @case('snacks')
                        <i class="fas fa-apple-alt category-icon"></i>
                        @break
                    @case('cold')
                        <i class="fas fa-glass-water category-icon"></i>
                        @break
                    @default
                        <i class="fas fa-shopping-bag category-icon"></i>
                @endswitch
                <h3 class="category-name">{{ $category['name'] }}</h3>
            </div>
        </a>
        @endforeach
    </div>

    <script>
        function filterByCategory(category) {
            // Remove active class from all buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active class to clicked button
            event.target.classList.add('active');
            
            // Filter category cards
            document.querySelectorAll('.category-card').forEach(card => {
                const cardLink = card.closest('.category-card-link');
                if (category === 'all' || card.dataset.category === category) {
                    cardLink.style.display = 'block';
                } else {
                    cardLink.style.display = 'none';
                }
            });
        }
    </script>
@endsection
