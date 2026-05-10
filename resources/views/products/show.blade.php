@extends('layouts.app')

@section('title', $product['name'])
@section('page_title', $product['name'])

@section('styles')
    <style>
        .product-detail-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .product-image-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 25px;
            border: 2px solid #8fbc8f;
            text-align: center;
        }

        .product-image-placeholder {
            width: 100%;
            height: 250px;
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 14px;
        }

        .product-info-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 25px;
            border: 2px solid #8fbc8f;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #5a7248;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #4a5d3a;
            transform: translateX(-3px);
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

        .radio-group {
            display: flex;
            gap: 15px;
            margin-top: 8px;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .radio-label input[type="radio"] {
            margin: 0;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .product-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: #5a7248;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .navigation-links {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .separator {
            color: #6c757d;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .product-detail-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .product-image-section,
            .product-info-section {
                padding: 20px;
            }

            .product-image-placeholder {
                height: 200px;
            }

            .product-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
                top: 15px;
                right: 15px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Back Link -->
    @php
        $categorySlug = $product['category'] === 'Bebidas Frías' ? 'cold' : 
                       ($product['category'] === 'Bebidas calientes' ? 'hot' : 
                       ($product['category'] === 'Tés e Infusiones' ? 'tea' : 
                       ($product['category'] === 'Repostería' ? 'bakery' : 'snacks')));
    @endphp
    <div class="navigation-links">
        <a href="{{ route('products.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Todos los Productos
        </a>
        <span class="separator">|</span>
        <a href="{{ route('products.category', $categorySlug) }}" class="back-link">
            {{ $product['category'] }}
        </a>
    </div>

    <!-- Product Detail Container -->
    <div class="product-detail-container">
        <!-- Product Image Section -->
        <div class="product-image-section">
            @if(!empty($product['image']))
                <img src="/images/{{ $product['image'] }}" alt="{{ $product['name'] }}" style="width: 100%; height: 250px; object-fit: cover; border-radius: 8px;">
            @else
                <div class="product-image-placeholder">
                    <i class="fas fa-image fa-2x"></i>
                </div>
            @endif
        </div>

        <!-- Product Info Section -->
        <div class="product-info-section">
            <form>
                <!-- Product Name -->
                <div class="form-group">
                    <label class="form-label">Nombre del producto</label>
                    <input type="text" class="form-control" value="{{ $product['name'] }}" readonly>
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label class="form-label">Categoría</label>
                    <input type="text" class="form-control" value="{{ $product['category'] }}" readonly>
                </div>

                <!-- Sizes -->
                <div class="form-group">
                    <label class="form-label">Tamaños disponibles</label>
                    <input type="text" class="form-control" value="Grande, Mediano, Pequeño" readonly>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label class="form-label">Estatus</label>
                    <div class="status-badge {{ $product['status'] === 'Activo' ? 'status-active' : 'status-inactive' }}">
                        {{ $product['status'] }}
                    </div>
                </div>

                <!-- Recipe -->
                <div class="form-group">
                    <label class="form-label">Receta</label>
                    <textarea class="form-control" rows="3" readonly>{{ $product['recipe'] ?? $product['name'] }}</textarea>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Icon -->
    <div class="product-icon">
        <i class="fas fa-glass-water"></i>
    </div>

    <style>
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
            margin: 50px auto;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            margin: 0;
            color: #5a7248;
            font-weight: 600;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #6c757d;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #5a7248;
            box-shadow: 0 0 0 3px rgba(90, 114, 72, 0.1);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-primary {
            background: #5a7248;
            color: white;
        }

        .btn-primary:hover {
            background: #4a5d3a;
        }

        @media (max-width: 768px) {
            .modal-dialog {
                width: 95%;
                margin: 50px auto;
            }
        }
    </style>

    <script>
        let currentProduct = @json($product);
        let currentProducts = []; // Array local para productos

        function saveProduct() {
            // Actualizar el producto en el array currentProducts
            const productIndex = currentProducts.findIndex(p => p.id == productId);
            if (productIndex !== -1) {
                currentProducts[productIndex] = { ...currentProducts[productIndex], ...productData };
                console.log('Updated product in array:', currentProducts[productIndex]);
                
                // Actualizar la información en la página
                updateProductInfo(productData);
                
                // Mostrar mensaje de éxito
                showSuccessMessage('Producto actualizado: ' + productData.name);
            } else {
                console.error('Product not found in array');
                showErrorMessage('No se encontró el producto para actualizar');
            }
            
            closeModal();
        }

        function updateProductInfo(productData) {
            // Actualizar la información del producto en la página
            const nameInput = document.querySelector('input[value="' + currentProduct.name + '"]');
            if (nameInput) nameInput.value = productData.name;
            
            const categoryInput = document.querySelector('input[value="' + currentProduct.category + '"]');
            if (categoryInput) categoryInput.value = productData.category;
            
            currentProduct = { ...currentProduct, ...productData };
        }

        function closeModal() {
            const modal = document.getElementById('editProductModal');
            modal.classList.remove('show');
        }

        function showSuccessMessage(message) {
            // Crear y mostrar un toast de éxito
            const toast = document.createElement('div');
            toast.className = 'alert alert-success position-fixed top-0 end-0 m-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                ${message}
            `;
            document.body.appendChild(toast);
            
            // Eliminar después de 3 segundos
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        function showErrorMessage(message) {
            // Crear y mostrar un toast de error
            const toast = document.createElement('div');
            toast.className = 'alert alert-danger position-fixed top-0 end-0 m-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>
                ${message}
            `;
            document.body.appendChild(toast);
            
            // Eliminar después de 3 segundos
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endsection
