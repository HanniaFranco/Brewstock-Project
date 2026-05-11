@extends('layouts.app')

@section('title', isset($recipe) ? $recipe['name'] : 'Nueva Receta')
@section('page_title', 'Recetas')

@section('styles')
    <style>
        .recipe-detail-container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .recipe-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .back-button {
            background: none;
            border: none;
            color: #5a7248;
            font-size: 24px;
            cursor: pointer;
            margin-right: 15px;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: rgba(90, 114, 72, 0.1);
            transform: translateX(-2px);
        }

        .recipe-title {
            color: #5a7248;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }

        .recipe-image-section {
            margin-bottom: 30px;
        }

        .recipe-image-container {
            position: relative;
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #8fbc8f 0%, #5a7248 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 64px;
            overflow: hidden;
        }

        .recipe-image-placeholder {
            text-align: center;
        }

        .recipe-image-placeholder i {
            margin-bottom: 10px;
        }

        .recipe-image-placeholder p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
        }

        .image-buttons {
            position: absolute;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }

        .image-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-btn.food {
            color: #5a7248;
        }

        .image-btn.remove {
            color: #dc3545;
        }

        .image-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .recipe-form {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 2px solid #8fbc8f;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #5a7248;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-control:focus {
            outline: none;
            border-color: #5a7248;
            background: white;
            box-shadow: 0 0 0 3px rgba(90, 114, 72, 0.1);
        }

        .form-control.select {
            cursor: pointer;
        }

        .form-control.textarea {
            min-height: 120px;
            resize: vertical;
            font-family: inherit;
        }

        .form-control.textarea.large {
            min-height: 200px;
        }

        .ingredients-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .ingredient-tag {
            background: #f0f7f0;
            color: #5a7248;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid #d0e0d0;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #5a7248 0%, #8fbc8f 100%);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(90, 114, 72, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(90, 114, 72, 0.4);
        }

        @media (max-width: 768px) {
            .recipe-detail-container {
                padding: 15px;
            }

            .recipe-header {
                margin-bottom: 20px;
            }

            .recipe-title {
                font-size: 24px;
            }

            .recipe-image-container {
                height: 200px;
                font-size: 48px;
            }

            .recipe-form {
                padding: 20px;
            }

            .form-actions {
                justify-content: center;
            }

            .btn-primary {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="recipe-detail-container">
        <!-- Header -->
        <div class="recipe-header">
            <button class="back-button" onclick="goBack()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1 class="recipe-title">Recetas</h1>
        </div>

        <!-- Recipe Image Section -->
        <div class="recipe-image-section">
            <div class="recipe-image-container">
                @if(isset($recipe) && $recipe['image'])
                    <img src="{{ asset($recipe['image']) }}" alt="{{ $recipe['name'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div class="recipe-image-placeholder">
                        <i class="fas fa-coffee"></i>
                        <p>{{ isset($recipe) ? $recipe['name'] : 'Nueva Receta' }}</p>
                    </div>
                @endif
                <div class="image-buttons">
                    <button class="image-btn food" onclick="changeImage()">
                        <i class="fas fa-camera"></i> Food Image
                    </button>
                    @if(isset($recipe) && $recipe['image'])
                        <button class="image-btn remove" onclick="removeImage()">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recipe Form -->
        <div class="recipe-form">
            <form id="recipeForm">
                <!-- Category -->
                <div class="form-group">
                    <label class="form-label" for="category">Categoría</label>
                    <select class="form-control select" id="category" name="category">
                        <option value="bebida-fria" {{ isset($recipe) && $recipe['category'] == 'bebida-fria' ? 'selected' : (isset($recipe) ? '' : 'selected') }}>Bebida fría</option>
                        <option value="bebida-caliente" {{ isset($recipe) && $recipe['category'] == 'bebida-caliente' ? 'selected' : '' }}>Bebida caliente</option>
                        <option value="postre" {{ isset($recipe) && $recipe['category'] == 'postre' ? 'selected' : '' }}>Postre</option>
                        <option value="snack" {{ isset($recipe) && $recipe['category'] == 'snack' ? 'selected' : '' }}>Snack</option>
                        <option value="desayuno" {{ isset($recipe) && $recipe['category'] == 'desayuno' ? 'selected' : '' }}>Desayuno</option>
                    </select>
                </div>

                <!-- Name -->
                <div class="form-group">
                    <label class="form-label" for="name">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ isset($recipe) ? $recipe['name'] : '' }}" placeholder="Nombre de la receta">
                </div>

                <!-- Ingredients -->
                <div class="form-group">
                    <label class="form-label" for="ingredients">Ingredientes</label>
                    <textarea class="form-control textarea" id="ingredients" name="ingredients" placeholder="Lista de ingredientes (uno por línea)">{{ isset($recipe) && is_array($recipe['ingredients']) ? app('App\\Http\\Controllers\\InventoryController')->formatIngredientsForTextarea($recipe['ingredients']) : (isset($recipe) ? $recipe['ingredients'] : '') }}</textarea>
                    <div class="ingredients-list">
                        @if(isset($recipe) && is_array($recipe['ingredients']))
                            @foreach($recipe['ingredients'] as $ingredient)
                                @if(is_array($ingredient) && isset($ingredient['name']))
                                    <span class="ingredient-tag">
                                        {{ $ingredient['name'] }}: {{ $ingredient['quantity'] }} {{ $ingredient['unit'] }}
                                    </span>
                                @elseif(isset($ingredient['ingredient']))
                                    <span class="ingredient-tag">
                                        {{ $ingredient['ingredient']['name'] }}: {{ $ingredient['quantity_required'] }} {{ $ingredient['ingredient']['unit'] }}
                                    </span>
                                @endif
                            @endforeach
                        @elseif(isset($recipe) && $recipe['ingredients'])
                            @php
                                $ingredientsArray = explode("\n", $recipe['ingredients']);
                                foreach($ingredientsArray as $ingredient) {
                                    $ingredient = trim($ingredient);
                                    if($ingredient) {
                                        echo '<span class="ingredient-tag">' . htmlspecialchars($ingredient) . '</span>';
                                    }
                                }
                            @endphp
                        @endif
                    </div>
                </div>

                <!-- Procedure -->
                <div class="form-group">
                    <label class="form-label" for="procedure">Procedimiento</label>
                    <textarea class="form-control textarea large" id="procedure" name="procedure" placeholder="Describe el procedimiento paso a paso">{{ isset($recipe) ? $recipe['procedure'] : '' }}</textarea>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> {{ isset($recipe) ? 'Actualizar' : 'Agregar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        function changeImage() {
            // Simular cambio de imagen
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.onchange = function(e) {
                const file = e.target.files[0];
                if (file) {
                    console.log('Imagen seleccionada:', file.name);
                    // Aquí puedes subir la imagen y actualizar la vista
                    alert('Función para cambiar imagen: ' + file.name);
                }
            };
            input.click();
        }

        function removeImage() {
            if (confirm('¿Estás seguro de que quieres eliminar la imagen?')) {
                console.log('Imagen eliminada');
                // Aquí puedes eliminar la imagen y mostrar el placeholder
                alert('Imagen eliminada');
            }
        }

        // Manejar envío del formulario
        document.getElementById('recipeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const recipeData = {
                category: formData.get('category'),
                name: formData.get('name'),
                ingredients: formData.get('ingredients'),
                procedure: formData.get('procedure')
            };
            
            console.log('Datos de la receta:', recipeData);
            
            // Determinar si es creación o actualización
            const isEditing = {{ isset($recipe) ? 'true' : 'false' }};
            const url = isEditing ? `/inventory/recipes/{{ $recipe['slug'] ?? '' }}` : '/inventory/recipes';
            const method = isEditing ? 'PUT' : 'POST';
            
            // Enviar datos al backend
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(recipeData)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert(result.message);
                    if (!isEditing && result.redirect) {
                        window.location.href = result.redirect;
                    } else {
                        window.location.href = '/inventory/recipes';
                    }
                } else {
                    alert(result.message || 'Error al guardar la receta');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión al guardar la receta');
            });
        });

        // Actualizar tags de ingredientes cuando cambia el textarea
        document.getElementById('ingredients').addEventListener('input', function() {
            const ingredients = this.value.split('\n').filter(ing => ing.trim());
            const tagsContainer = this.nextElementSibling;
            
            tagsContainer.innerHTML = ingredients.map(ing => 
                `<span class="ingredient-tag">${ing.trim()}</span>`
            ).join('');
        });

    </script>
@endsection
