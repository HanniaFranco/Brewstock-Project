@extends('layouts.app')

@section('title', 'Inventario')
@section('page_title', 'Inventario')

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

            .floating-btn {
                bottom: 20px;
                right: 20px;
            }
        }

        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #5a7248 0%, #8fbc8f 100%);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(90, 114, 72, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .floating-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(90, 114, 72, 0.4);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal.show {
            display: block;
        }

        .modal-dialog {
            position: relative;
            width: 90%;
            max-width: 500px;
            margin: 100px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
        }

        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px;
            border-bottom: 1px solid #e0e0e0;
        }

        .modal-title {
            margin: 0;
            color: #5a7248;
            font-size: 18px;
            font-weight: 600;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 20px;
            color: #6c757d;
            cursor: pointer;
            padding: 5px;
        }

        .btn-close:hover {
            color: #495057;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 20px 25px;
            border-top: 1px solid #e0e0e0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #5a7248;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #5a7248;
            box-shadow: 0 0 0 3px rgba(90, 114, 72, 0.1);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #5a7248;
            color: white;
        }

        .btn-primary:hover {
            background: #4a5d3a;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
@endsection

@section('content')
    
    <!-- Categories Grid -->
    <div class="categories-grid">
        @foreach($ingredientCategories as $category)
            <a href="#" class="category-card" onclick="handleCategoryClick('{{ Str::slug($category->category) }}')">
                <div class="category-icon">
                    <i class="fas fa-flask"></i>
                </div>
                <h3 class="category-name">{{ $category->category }}</h3>
                <p class="category-count">{{ $category->count }} ingredientes</p>
            </a>
        @endforeach

        @if($ingredientsWithoutCategory > 0)
            <a href="#" class="category-card" onclick="handleCategoryClick('sin-categoria')">
                <div class="category-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <h3 class="category-name">Sin categoría</h3>
                <p class="category-count">{{ $ingredientsWithoutCategory }} ingredientes</p>
            </a>
        @endif
    </div>

    <!-- Floating Button for Creating Categories -->
    <button class="floating-btn" onclick="openCategoryModal()" title="Crear Nueva Categoría">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Crear Nueva Categoría</h5>
                    <button type="button" class="btn-close" onclick="closeCategoryModal()" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm" onsubmit="event.preventDefault(); saveCategory();">
                        <div class="form-group">
                            <label for="categoryName" class="form-label">Nombre de la Categoría</label>
                            <input type="text" class="form-control" id="categoryName" name="name" required placeholder="Ej: Lácteos, Frutas, Granos">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeCategoryModal()">Cancelar</button>
                    <button type="submit" form="categoryForm" class="btn btn-primary">Crear Categoría</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handleCategoryClick(categorySlug) {
            // Redirigir a la página de ingredientes filtrada por categoría
            window.location.href = `{{ route('inventory.ingredients') }}?category=${categorySlug}`;
        }

        function openCategoryModal() {
            const modal = document.getElementById('categoryModal');
            modal.classList.add('show');
            document.getElementById('categoryForm').reset();
        }

        function closeCategoryModal() {
            const modal = document.getElementById('categoryModal');
            modal.classList.remove('show');
        }

        async function saveCategory() {
            const form = document.getElementById('categoryForm');
            const formData = new FormData(form);
            const categoryName = formData.get('name');

            if (!categoryName) {
                showErrorMessage('Por favor ingresa un nombre para la categoría');
                return;
            }

            try {
                const response = await fetch('{{ route('inventory.categories.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name: categoryName })
                });

                const result = await response.json();

                if (result.success) {
                    showSuccessMessage('Categoría creada correctamente');
                    closeCategoryModal();
                    // Recargar la página para mostrar la nueva categoría
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showErrorMessage(result.message || 'Error al crear la categoría');
                }
            } catch (error) {
                console.error('Error creating category:', error);
                showErrorMessage('Error de conexión al crear la categoría');
            }
        }

        function showSuccessMessage(message) {
            // Simple success notification
            const alert = document.createElement('div');
            alert.className = 'alert alert-success';
            alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 15px 20px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 6px;';
            alert.textContent = message;
            document.body.appendChild(alert);
            setTimeout(() => alert.remove(), 3000);
        }

        function showErrorMessage(message) {
            // Simple error notification
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger';
            alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 15px 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 6px;';
            alert.textContent = message;
            document.body.appendChild(alert);
            setTimeout(() => alert.remove(), 3000);
        }
    </script>
@endsection
