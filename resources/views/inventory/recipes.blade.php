@extends('layouts.app')

@section('title', 'Recetas')
@section('page_title', 'Recetas')

@section('styles')
    <style>
        .recipes-container {
            padding: 20px;
        }

        .recipes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 80px;
        }

        .recipe-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .recipe-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .recipe-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #8fbc8f 0%, #5a7248 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            position: relative;
        }

        .recipe-category {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(255, 255, 255, 0.9);
            color: #5a7248;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .recipe-content {
            padding: 20px;
        }

        .recipe-name {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .recipe-description {
            font-size: 14px;
            color: #666;
            margin: 0 0 16px 0;
            line-height: 1.4;
        }

        .recipe-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid #f0f0f0;
        }

        .recipe-time {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #666;
        }

        .recipe-time i {
            color: #5a7248;
        }

        .recipe-difficulty {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            font-weight: 600;
        }

        .difficulty-easy {
            color: #28a745;
        }

        .difficulty-medium {
            color: #ffc107;
        }

        .difficulty-hard {
            color: #dc3545;
        }

        .fab-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #5a7248 0%, #8fbc8f 100%);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(90, 114, 72, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .fab-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(90, 114, 72, 0.4);
        }

        @media (max-width: 768px) {
            .recipes-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .recipe-card {
                margin: 0 10px;
            }

            .fab-button {
                bottom: 20px;
                right: 20px;
                width: 56px;
                height: 56px;
                font-size: 20px;
            }

            .no-recipes {
                grid-column: 1 / -1;
                text-align: center;
                padding: 60px 20px;
                color: #6c757d;
            }

            .no-recipes i {
                font-size: 48px;
                margin-bottom: 16px;
                color: #adb5bd;
            }

            .no-recipes p {
                font-size: 16px;
                margin: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="recipes-container">
        <!-- Recomendaciones Inteligentes (con botón toggle) -->
        @include('partials.recipe-recommendations-toggle', ['recommendedRecipes' => $recommendedRecipes ?? []])

        <!-- Recipes Grid -->
        <div class="recipes-grid" id="recipesGrid">
            @forelse($recipes ?? [] as $recipe)
                @php
                    $difficultyClass = match($recipe['difficulty']) {
                        'Difícil' => 'difficulty-hard',
                        'Medio' => 'difficulty-medium',
                        default => 'difficulty-easy'
                    };
                    $categoryIcon = match($recipe['category']) {
                        'Cafe Frio' => 'fa-glass-whiskey',
                        'Postre' => 'fa-cookie',
                        default => 'fa-mug-hot'
                    };
                @endphp
                <div class="recipe-card" data-slug="{{ $recipe['slug'] }}">
                    <div class="recipe-image">
                        <i class="fas {{ $categoryIcon }}"></i>
                        <span class="recipe-category">{{ $recipe['category'] }}</span>
                    </div>
                    <div class="recipe-content">
                        <h3 class="recipe-name">{{ $recipe['name'] }}</h3>
                        <p class="recipe-description">{{ $recipe['description'] }}</p>
                        <div class="recipe-meta">
                            <div class="recipe-time">
                                <i class="fas fa-clock"></i>
                                <span>{{ $recipe['time'] }}</span>
                            </div>
                            <div class="recipe-difficulty {{ $difficultyClass }}">
                                <i class="fas fa-star"></i>
                                <span>{{ $recipe['difficulty'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="no-recipes">
                    <i class="fas fa-info-circle"></i>
                    <p>No hay recetas disponibles. Crea tu primera receta.</p>
                </div>
            @endforelse
        </div>

        <!-- Floating Action Button -->
        <button class="fab-button" onclick="openAddRecipeModal()">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <script>
        function openAddRecipeModal() {
            // Redirigir a página de receta individual vacía
            window.location.href = '/inventory/recipes/create';
        }

        // Función para ver detalles de receta
        document.querySelectorAll('.recipe-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('.fab-button')) {
                    const recipeSlug = this.dataset.slug;
                    const recipeName = this.querySelector('.recipe-name').textContent;
                    console.log('Ver receta:', recipeName, 'Slug:', recipeSlug);
                    
                    // Redirigir a página individual de la receta
                    if (recipeSlug) {
                        window.location.href = `/inventory/recipes/${recipeSlug}`;
                    }
                }
            });
        });

        // Funciones de cache para recetas
        function loadRecipesFromCache() {
            try {
                return JSON.parse(localStorage.getItem('recipes_cache') || '[]');
            } catch (e) {
                console.error('Error cargando recetas desde cache:', e);
                return [];
            }
        }

        function saveRecipesToCache(recipes) {
            try {
                localStorage.setItem('recipes_cache', JSON.stringify(recipes));
                console.log('Recetas guardadas en cache:', recipes);
            } catch (e) {
                console.error('Error guardando recetas en cache:', e);
            }
        }

        function addRecipeToGrid(recipe) {
            const grid = document.getElementById('recipesGrid');
            const categoryLabels = {
                'bebida-fria': 'Bebida Fría',
                'bebida-caliente': 'Bebida Caliente',
                'postre': 'Postre',
                'snack': 'Snack',
                'desayuno': 'Desayuno'
            };

            const categoryIcons = {
                'bebida-fria': 'fa-glass-whiskey',
                'bebida-caliente': 'fa-mug-hot',
                'postre': 'fa-cookie',
                'snack': 'fa-cookie-bite',
                'desayuno': 'fa-bread-slice'
            };

            const difficultyColors = {
                'facil': 'difficulty-easy',
                'medio': 'difficulty-medium',
                'dificil': 'difficulty-hard'
            };

            const newCard = document.createElement('div');
            newCard.className = 'recipe-card';
            newCard.innerHTML = `
                <div class="recipe-image">
                    <i class="fas ${categoryIcons[recipe.category] || 'fa-coffee'}"></i>
                    <span class="recipe-category">${categoryLabels[recipe.category] || recipe.category}</span>
                </div>
                <div class="recipe-content">
                    <h3 class="recipe-name">${recipe.name}</h3>
                    <p class="recipe-description">Receta personalizada creada por ti.</p>
                    <div class="recipe-meta">
                        <div class="recipe-time">
                            <i class="fas fa-clock"></i>
                            <span>Personalizado</span>
                        </div>
                        <div class="recipe-difficulty difficulty-easy">
                            <i class="fas fa-star"></i>
                            <span>Personal</span>
                        </div>
                    </div>
                </div>
            `;

            // Agregar evento de click
            newCard.addEventListener('click', function(e) {
                if (!e.target.closest('.fab-button')) {
                    console.log('Navegando a receta:', recipe.slug);
                    window.location.href = `/inventory/recipes/${recipe.slug}`;
                }
            });

            // Insertar al principio del grid
            if (grid.firstChild) {
                grid.insertBefore(newCard, grid.firstChild);
            } else {
                grid.appendChild(newCard);
            }

            // Animación de entrada
            newCard.style.backgroundColor = '#d4edda';
            setTimeout(() => {
                newCard.style.backgroundColor = '';
            }, 3000);
        }

        function updateRecipeInGrid(updatedRecipe) {
            const cards = document.querySelectorAll('.recipe-card');
            cards.forEach(card => {
                const nameElement = card.querySelector('.recipe-name');
                if (nameElement && nameElement.textContent.toLowerCase().includes(updatedRecipe.name.toLowerCase())) {
                    // Actualizar nombre
                    nameElement.textContent = updatedRecipe.name;
                    
                    // Actualizar categoría si es necesario
                    const categoryElement = card.querySelector('.recipe-category');
                    const categoryLabels = {
                        'bebida-fria': 'Bebida Fría',
                        'bebida-caliente': 'Bebida Caliente',
                        'postre': 'Postre',
                        'snack': 'Snack',
                        'desayuno': 'Desayuno'
                    };
                    if (categoryElement) {
                        categoryElement.textContent = categoryLabels[updatedRecipe.category] || updatedRecipe.category;
                    }

                    // Animación de actualización
                    card.style.backgroundColor = '#fff3cd';
                    setTimeout(() => {
                        card.style.backgroundColor = '';
                    }, 2000);

                    // Actualizar evento de click con nuevo slug
                    const newCard = card.cloneNode(true);
                    newCard.addEventListener('click', function(e) {
                        if (!e.target.closest('.fab-button')) {
                            window.location.href = `/inventory/recipes/${updatedRecipe.slug}`;
                        }
                    });
                    card.replaceWith(newCard);
                }
            });
        }

        // Función para cargar recetas desde cache
        function loadCachedRecipes() {
            const cachedRecipes = loadRecipesFromCache();
            
            cachedRecipes.forEach(recipe => {
                // Buscar si ya existe un card con el mismo nombre
                const existingCard = Array.from(document.querySelectorAll('.recipe-card')).find(card => {
                    const nameElement = card.querySelector('.recipe-name');
                    return nameElement && nameElement.textContent.toLowerCase() === recipe.name.toLowerCase();
                });
                
                if (existingCard) {
                    // Si existe, actualizarlo
                    updateRecipeInGrid(recipe);
                } else {
                    // Si no existe, agregarlo
                    addRecipeToGrid(recipe);
                }
            });
        }

        // Cargar recetas desde cache al iniciar la página
        document.addEventListener('DOMContentLoaded', function() {
            loadCachedRecipes();
        });

        // También cargar cuando la página gana foco (al regresar de otra pestaña)
        window.addEventListener('focus', function() {
            setTimeout(loadCachedRecipes, 100);
        });
    </script>
@endsection
