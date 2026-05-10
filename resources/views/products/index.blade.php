@extends('layouts.app')

@section('title', 'Productos')
@section('page_title', 'Todos los Productos')

@section('styles')
    <style>
        .page-header {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            border: 2px solid #8fbc8f;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            color: #5a7248;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .add-product-btn {
            background: #5a7248;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .add-product-btn:hover {
            background: #4a5d3a;
            transform: translateY(-1px);
        }

        .products-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 2px solid #8fbc8f;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #8fbc8f;
            color: #5a7248;
            font-weight: 600;
            padding: 15px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .product-image-placeholder {
            width: 50px;
            height: 50px;
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 12px;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .product-name {
            font-weight: 500;
            color: #333;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 6px 10px;
            border: 1px solid #dee2e6;
            background: white;
            color: #6c757d;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-action:hover {
            background: #f8f9fa;
            color: #495057;
            transform: translateY(-1px);
        }

        .btn-action.view {
            color: #007bff;
            border-color: #007bff;
        }

        .btn-action.view:hover {
            background: #007bff;
            color: white;
        }

        .btn-action.edit {
            color: #ffc107;
            border-color: #ffc107;
        }

        .btn-action.edit:hover {
            background: #ffc107;
            color: #212529;
        }

        .btn-action.delete {
            color: #dc3545;
            border-color: #dc3545;
        }

        .btn-action.delete:hover {
            background: #dc3545;
            color: white;
        }

        .btn-action.toggle {
            color: #6c757d;
            border-color: #6c757d;
        }

        .btn-action.toggle:hover {
            background: #6c757d;
            color: white;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .page-title {
                font-size: 20px;
            }

            .table thead th,
            .table tbody td {
                padding: 10px;
                font-size: 13px;
            }

            .product-image-placeholder {
                width: 40px;
                height: 40px;
                font-size: 10px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Todos los Productos</h1>
        <button class="add-product-btn" onclick="openAddProductModal()">
            <i class="fas fa-plus"></i>
            Agregar Producto
        </button>
    </div>

    <!-- Products Table -->
    <div class="products-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Imagen</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Estatus</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allProducts ?? $products as $product)
                    <tr>
                        <td class="product-name">{{ $product['name'] }}</td>
                        <td>
                            @if($product['image'])
                                <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] }}" class="product-image">
                            @else
                                <div class="product-image-placeholder"></div>
                            @endif
                        </td>
                        <td>{{ $product['category'] }}</td>
                        <td>${{ number_format($product['price'], 2) }}</td>
                        <td>
                            <span class="badge {{ $product['status'] === 'Activo' ? 'badge-success' : 'badge-danger' }}">
                                {{ $product['status'] }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                               @php
                                    $categorySlug = match($product['category']) {
                                        'Bebidas Frías' => 'cold',
                                        'Bebidas calientes' => 'hot',
                                        'Tés e Infusiones' => 'tea',
                                        'Repostería' => 'bakery',
                                        default => 'snacks',
                                    };
                                @endphp
                                <a href="{{ route('products.show', ['category' => $categorySlug, 'product' => $product['id']]) }}" class="btn-action view" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn-action edit" title="Editar" onclick="openEditModal({{ $product['id'] }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action delete" title="Eliminar" onclick="deleteProduct({{ $product['id'] }}, '{{ $product['name'] }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn-action toggle" title="{{ $product['status'] === 'Activo' ? 'Desactivar' : 'Activar' }}" onclick="toggleProductStatus({{ $product['id'] }}, '{{ $product['status'] }}')">
                                    <i class="fas fa-toggle-{{ $product['status'] === 'Activo' ? 'on' : 'off' }}"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="no-products">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No hay productos registrados</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                    <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <input type="hidden" id="editProductId" name="id">
                        
                        <div class="form-group">
                            <label for="editProductName" class="form-label">Nombre del producto</label>
                            <input type="text" class="form-control" id="editProductName" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="editProductCategory" class="form-label">Categoría</label>
                            <select class="form-control" id="editProductCategory" name="category" required>
                                <option value="">Seleccionar categoría</option>
                                <option value="Bebidas Frías">Bebidas Frías</option>
                                <option value="Bebidas calientes">Bebidas calientes</option>
                                <option value="Tés e Infusiones">Tés e Infusiones</option>
                                <option value="Repostería">Repostería</option>
                                <option value="Snacks">Snacks</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editProductPrice" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="editProductPrice" name="price" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="editProductImage" class="form-label">Imagen (opcional)</label>
                            <input type="file" class="form-control" id="editProductImage" name="image" accept="image/*">
                            <input type="hidden" id="editProductImageName" name="image_name">
                            <div id="editProductImagePreview" class="image-preview mt-2"></div>
                        </div>

                        <div class="form-group">
                            <label for="editProductStatus" class="form-label">Estatus</label>
                            <select class="form-control" id="editProductStatus" name="status" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="saveProduct()">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Agregar Nuevo Producto</h5>
                    <button type="button" class="btn-close" onclick="closeAddProductModal()" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="form-group">
                            <label for="addProductName" class="form-label">Nombre del producto</label>
                            <input type="text" class="form-control" id="addProductName" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="addProductCategory" class="form-label">Categoría</label>
                            <select class="form-control" id="addProductCategory" name="category" required>
                                <option value="">Seleccionar categoría</option>
                                <option value="Bebidas Frías">Bebidas Frías</option>
                                <option value="Bebidas calientes">Bebidas calientes</option>
                                <option value="Tés e Infusiones">Tés e Infusiones</option>
                                <option value="Repostería">Repostería</option>
                                <option value="Snacks">Snacks</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="addProductPrice" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="addProductPrice" name="price" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="addProductStatus" class="form-label">Estatus</label>
                            <select class="form-control" id="addProductStatus" name="status" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="addProductImage" class="form-label">Imagen (opcional)</label>
                            <input type="file" class="form-control" id="addProductImage" name="image" accept="image/*">
                            <input type="hidden" id="addProductImageName" name="image_name">
                            <div id="addProductImagePreview" class="image-preview mt-2"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddProductModal()">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="addProduct()">Agregar Producto</button>
                </div>
            </div>
        </div>
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

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 20px 25px;
            border-top: 1px solid #e0e0e0;
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
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .modal-dialog {
                width: 95%;
                margin: 50px auto;
            }
        }

        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #f8f9fa;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .image-preview .placeholder {
            color: #6c757d;
            font-size: 14px;
            text-align: center;
            padding: 20px;
        }
    </style>

    <script>
        // Cargar productos desde localStorage SOLO si existe cache válido para esta página específica
        let hasCache = false;
        let cacheKey = 'products_index_cache'; // Cache específico para esta página
        let cachedProducts = loadProductsFromCache();
        let currentProducts;
        
        if (cachedProducts && cachedProducts.length > 0) {
            currentProducts = cachedProducts;
            hasCache = true;
            console.log('Using cached products for index page:', currentProducts);
        } else {
            currentProducts = @json($allProducts ?? $products);
            console.log('Using original products for index page:', currentProducts);
            // Limpiar cualquier cache residual de esta página
            clearProductsCache();
        }
        
        function loadProductsFromCache() {
            try {
                const cached = localStorage.getItem(cacheKey);
                return cached ? JSON.parse(cached) : null;
            } catch (e) {
                console.error('Error loading products from cache:', e);
                return null;
            }
        }
        
        function saveProductsToCache() {
            try {
                localStorage.setItem(cacheKey, JSON.stringify(currentProducts));
                console.log('Products saved to cache for index page');
            } catch (e) {
                console.error('Error saving products to cache:', e);
            }
        }
        
        function clearProductsCache() {
            try {
                localStorage.removeItem(cacheKey);
                console.log('Products cache cleared for index page');
            } catch (e) {
                console.error('Error clearing products cache:', e);
            }
        }

        function openEditModal(productId) {
            console.log('openEditModal called with productId:', productId);
            console.log('currentProducts available:', currentProducts);
            
            let product = currentProducts.find(p => p.id == productId);
            console.log('found product in currentProducts:', product);
            
            // Si no se encuentra el producto en currentProducts, recargar desde los datos originales
            if (!product) {
                console.log('Product not found in currentProducts, loading from original data...');
                const originalProducts = @json($allProducts ?? $products);
                product = originalProducts.find(p => p.id == productId);
                console.log('found product in original data:', product);
            }
            
            if (!product) {
                console.error('Product not found with ID:', productId);
                alert('No se encontró el producto con ID: ' + productId);
                return;
            }

            document.getElementById('editProductId').value = product.id;
            document.getElementById('editProductName').value = product.name;
            document.getElementById('editProductCategory').value = product.category;
            document.getElementById('editProductPrice').value = product.price;
            document.getElementById('editProductStatus').value = product.status;

            const modal = document.getElementById('editProductModal');
            modal.removeAttribute('aria-hidden');
            modal.classList.add('show');
            console.log('Modal opened successfully');
        }

        function closeModal() {
            const modal = document.getElementById('editProductModal');
            // Remover focus del elemento antes de ocultar el modal
            const activeElement = document.activeElement;
            if (activeElement && modal.contains(activeElement)) {
                activeElement.blur();
            }
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
        }

        async function saveProduct() {
            const form = document.getElementById('editProductForm');
            const formData = new FormData(form);
            
            const productId = parseInt(formData.get('id'));
            const imageFile = document.getElementById('editProductImage').files[0];
            
            // Crear FormData para la petición
            const requestData = new FormData();
            requestData.append('_method', 'PUT'); // Indicar a Laravel que es PUT
            requestData.append('id', productId);
            requestData.append('name', formData.get('name'));
            requestData.append('category', formData.get('category'));
            requestData.append('price', formData.get('price'));
            requestData.append('status', formData.get('status'));
            
            if (imageFile) {
                requestData.append('image', imageFile);
            } else if (formData.get('image_name')) {
                requestData.append('image_name', formData.get('image_name'));
            }

            console.log('Saving product:', productId, Array.from(requestData.entries()));
            
            // Hacer llamada real al backend para actualizar
            const response = await fetch('/products/' + productId, {
                method: 'POST', // Usar POST con _method para PUT
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: requestData
            });

            const result = await response.json();

            if (result.success) {
                // Actualizar el array local
                const finalProductIndex = currentProducts.findIndex(p => p.id == productId);
                const updatedProductData = {
                    name: formData.get('name'),
                    category: formData.get('category'),
                    price: parseFloat(formData.get('price')),
                    status: formData.get('status'),
                    image: result.product?.image || formData.get('image_name')
                };
                
                if (finalProductIndex !== -1) {
                    currentProducts[finalProductIndex] = { ...currentProducts[finalProductIndex], ...updatedProductData };
                } else {
                    const originalProducts = @json($allProducts ?? $products);
                    const originalProduct = originalProducts.find(p => p.id == productId);
                    currentProducts.push({ ...originalProduct, ...updatedProductData });
                }
                
                // Guardar en cache
                saveProductsToCache();
                
                // Actualizar la tabla en el DOM
                updateProductInTable(productId, updatedProductData);
                
                // Actualizar el botón de toggle si el estatus cambió
                updateToggleButton(productId, updatedProductData.status);
                
                // Mostrar mensaje de éxito
                showSuccessMessage('Producto actualizado: ' + updatedProductData.name);
            } else {
                showErrorMessage(result.message || 'Error al actualizar el producto');
            }
            
            closeModal();
        }

        function updateProductInTable(productId, productData) {
            // Encontrar la fila del producto en la tabla
            const tableRows = document.querySelectorAll('table tbody tr');
            tableRows.forEach(row => {
                const editButton = row.querySelector('button[onclick*="' + productId + '"]');
                if (editButton) {
                    // Actualizar las celdas de la fila
                    const cells = row.querySelectorAll('td');
                    if (cells.length >= 6) {
                        cells[0].textContent = productData.name; // Nombre (columna 0)
                        
                        // Actualizar imagen si hay una nueva (columna 1)
                        if (productData.image) {
                            const imageCell = cells[1];
                            imageCell.innerHTML = `<img src="/images/${productData.image}" alt="${productData.name}" class="product-image">`;
                        }
                        
                        cells[2].textContent = productData.category; // Categoría (columna 2)
                        cells[3].textContent = '$' + parseFloat(productData.price).toFixed(2); // Precio (columna 3)
                        
                        // Actualizar el badge de estatus (columna 4)
                        const statusBadge = cells[4].querySelector('.badge');
                        if (statusBadge) {
                            statusBadge.textContent = productData.status;
                            statusBadge.className = 'badge ' + (productData.status === 'Activo' ? 'badge-success' : 'badge-danger');
                        }
                        
                        // Actualizar el botón de editar con el nuevo onclick (columna 5)
                        editButton.setAttribute('onclick', 'openEditModal(' + productId + ')');
                        
                        // Resaltar la fila para mostrar que se actualizó
                        row.style.backgroundColor = '#d4edda';
                        setTimeout(() => {
                            row.style.backgroundColor = '';
                        }, 2000);
                    }
                }
            });
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

        function deleteProduct(productId, productName) {
            console.log('Attempting to delete product:', productId, productName);
            if (confirm('¿Estás seguro de que quieres eliminar el producto "' + productName + '"?')) {
                // Eliminar el producto del array
                const productIndex = currentProducts.findIndex(p => p.id == productId);
                console.log('Product index found:', productIndex);
                console.log('Current products before delete:', currentProducts);
                
                if (productIndex !== -1) {
                    const deletedProduct = currentProducts[productIndex];
                    currentProducts.splice(productIndex, 1);
                    console.log('Product deleted from array. Current products:', currentProducts);
                    
                    // Guardar en cache
                    saveProductsToCache();
                    console.log('Cache saved after delete');
                    
                    // Eliminar la fila de la tabla
                    removeTableRow(productId);
                    
                    // Mostrar mensaje de éxito
                    showSuccessMessage('Producto eliminado: ' + productName);
                } else {
                    console.error('Product not found in array for deletion. Available IDs:', currentProducts.map(p => p.id));
                    showErrorMessage('No se encontró el producto para eliminar');
                }
            }
        }

        function toggleProductStatus(productId, currentStatus) {
            const newStatus = currentStatus === 'Activo' ? 'Inactivo' : 'Activo';
            
            // Actualizar el producto en el array
            const productIndex = currentProducts.findIndex(p => p.id == productId);
            if (productIndex !== -1) {
                currentProducts[productIndex].status = newStatus;
                
                // Guardar en cache
                saveProductsToCache();
                
                // Actualizar la tabla
                updateProductStatusInTable(productId, newStatus);
                
                // Actualizar el botón de toggle
                updateToggleButton(productId, newStatus);
                
                // Mostrar mensaje de éxito
                showSuccessMessage('Producto ' + (newStatus === 'Activo' ? 'activado' : 'desactivado') + ' correctamente');
            } else {
                showErrorMessage('No se encontró el producto para cambiar el estatus');
            }
        }

        function removeTableRow(productId) {
            const tableRows = document.querySelectorAll('table tbody tr');
            tableRows.forEach(row => {
                const deleteButton = row.querySelector('button[onclick*="deleteProduct"]');
                if (deleteButton) {
                    const onclickAttr = deleteButton.getAttribute('onclick');
                    const productIdMatch = onclickAttr.match(/deleteProduct\((\d+)/);
                    
                    if (productIdMatch && parseInt(productIdMatch[1]) === productId) {
                        console.log('Found row to delete:', productId);
                        
                        // Resaltar la fila en rojo antes de eliminarla
                        row.style.backgroundColor = '#f8d7da';
                        row.style.transition = 'all 0.3s ease';
                        
                        setTimeout(() => {
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(-20px)';
                            
                            setTimeout(() => {
                                row.remove();
                                console.log('Row removed successfully');
                            }, 300);
                        }, 500);
                        return; // Stop searching after finding the row
                    }
                }
            });
        }

        function updateProductStatusInTable(productId, newStatus) {
            const tableRows = document.querySelectorAll('table tbody tr');
            tableRows.forEach(row => {
                const toggleButton = row.querySelector('button[onclick*="toggleProductStatus"]');
                if (toggleButton) {
                    const onclickAttr = toggleButton.getAttribute('onclick');
                    const productIdMatch = onclickAttr.match(/toggleProductStatus\((\d+)/);
                    
                    if (productIdMatch && parseInt(productIdMatch[1]) === productId) {
                        console.log('Found row to update status:', productId, 'to', newStatus);
                        
                        // Actualizar el badge de estatus
                        const cells = row.querySelectorAll('td');
                        if (cells.length >= 5) {
                            const statusBadge = cells[4].querySelector('.badge');
                            if (statusBadge) {
                                statusBadge.textContent = newStatus;
                                statusBadge.className = 'badge ' + (newStatus === 'Activo' ? 'badge-success' : 'badge-danger');
                                console.log('Status badge updated:', newStatus);
                            }
                        }
                        
                        // Resaltar la fila
                        row.style.backgroundColor = '#fff3cd';
                        setTimeout(() => {
                            row.style.backgroundColor = '';
                        }, 2000);
                        return; // Stop searching after finding the row
                    }
                }
            });
        }

        function updateToggleButton(productId, newStatus) {
            const tableRows = document.querySelectorAll('table tbody tr');
            tableRows.forEach(row => {
                const toggleButton = row.querySelector('button[onclick*="toggleProductStatus"]');
                if (toggleButton) {
                    const onclickAttr = toggleButton.getAttribute('onclick');
                    const productIdMatch = onclickAttr.match(/toggleProductStatus\((\d+)/);
                    
                    if (productIdMatch && parseInt(productIdMatch[1]) === productId) {
                        console.log('Found toggle button to update:', productId, 'to', newStatus);
                        
                        // Actualizar el icono
                        const icon = toggleButton.querySelector('i');
                        if (icon) {
                            icon.className = 'fas fa-toggle-' + (newStatus === 'Activo' ? 'on' : 'off');
                            console.log('Toggle icon updated:', icon.className);
                        }
                        
                        // Actualizar el título
                        toggleButton.setAttribute('title', newStatus === 'Activo' ? 'Desactivar' : 'Activar');
                        
                        // Actualizar el onclick
                        toggleButton.setAttribute('onclick', 'toggleProductStatus(' + productId + ', \'' + newStatus + '\')');
                        console.log('Toggle button onclick updated');
                        return; // Stop searching after finding the button
                    }
                }
            });
        }

        // Reconstruir la tabla con datos cacheados SOLO si hay cache disponible
        function rebuildTableFromCache() {
            // Solo ejecutar si explícitamente hay cache disponible
            if (!hasCache) {
                console.log('No cache available, keeping original products');
                return;
            }
            
            console.log('Rebuilding table from cache:', currentProducts);
            
            // Actualizar todas las filas de la tabla
            const tableBody = document.querySelector('table tbody');
            if (tableBody) {
                const rows = tableBody.querySelectorAll('tr');
                const rowsToRemove = [];
                
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    if (cells.length >= 6) {
                        // Obtener el ID del producto desde cualquier botón de la fila
                        const editButton = row.querySelector('button[onclick*="openEditModal"]');
                        if (editButton) {
                            const onclickAttr = editButton.getAttribute('onclick');
                            const productIdMatch = onclickAttr.match(/openEditModal\((\d+)\)/);
                            
                            if (productIdMatch) {
                                const productId = parseInt(productIdMatch[1]);
                                const cachedProduct = currentProducts.find(p => p.id == productId);
                                
                                if (cachedProduct) {
                                    // Actualizar celdas con datos cacheados
                                    cells[0].textContent = cachedProduct.name;
                                    cells[2].textContent = cachedProduct.category;
                                    cells[3].textContent = '$' + parseFloat(cachedProduct.price).toFixed(2);
                                    
                                    // Actualizar badge de estatus
                                    const statusBadge = cells[4].querySelector('.badge');
                                    if (statusBadge) {
                                        statusBadge.textContent = cachedProduct.status;
                                        statusBadge.className = 'badge ' + (cachedProduct.status === 'Activo' ? 'badge-success' : 'badge-danger');
                                    }
                                    
                                    // Actualizar botones con datos correctos
                                    const deleteButton = row.querySelector('button[onclick*="deleteProduct"]');
                                    const toggleButton = row.querySelector('button[onclick*="toggleProductStatus"]');
                                    
                                    if (deleteButton) {
                                        deleteButton.setAttribute('onclick', 'deleteProduct(' + cachedProduct.id + ', \'' + cachedProduct.name.replace(/'/g, "\\'") + '\')');
                                    }
                                    if (toggleButton) {
                                        const icon = toggleButton.querySelector('i');
                                        if (icon) {
                                            icon.className = 'fas fa-toggle-' + (cachedProduct.status === 'Activo' ? 'on' : 'off');
                                        }
                                        toggleButton.setAttribute('title', cachedProduct.status === 'Activo' ? 'Desactivar' : 'Activar');
                                        toggleButton.setAttribute('onclick', 'toggleProductStatus(' + cachedProduct.id + ', \'' + cachedProduct.status + '\')');
                                    }
                                    
                                    // Resaltar la fila para mostrar que se actualizó desde cache
                                    row.style.backgroundColor = '#e7f3ff';
                                    setTimeout(() => {
                                        row.style.backgroundColor = '';
                                    }, 2000);
                                } else {
                                    // El producto fue eliminado, marcar fila para remover
                                    console.log('Product', productId, 'not found in cache, marking for removal');
                                    rowsToRemove.push(row);
                                }
                            }
                        }
                    }
                });
                
                // Eliminar filas de productos que fueron borrados
                rowsToRemove.forEach(row => {
                    row.style.backgroundColor = '#f8d7da';
                    row.style.transition = 'all 0.3s ease';
                    setTimeout(() => {
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            console.log('Removed deleted product row from table');
                        }, 300);
                    }, 500);
                });
            }
        }

        // Funciones para manejo de imágenes
        function handleImageUpload(input, previewId, hiddenInputId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            const hiddenInput = document.getElementById(hiddenInputId);
            
            if (file) {
                // Validar que sea una imagen
                if (!file.type.startsWith('image/')) {
                    showErrorMessage('Por favor selecciona un archivo de imagen válido');
                    input.value = '';
                    return;
                }
                
                // Validar tamaño (máximo 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showErrorMessage('La imagen no debe superar los 2MB');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    hiddenInput.value = file.name;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<div class="placeholder">Sin imagen</div>';
                hiddenInput.value = '';
            }
        }

        // Event listeners para uploads de imágenes
        document.addEventListener('DOMContentLoaded', function() {
            // Edit product image
            const editImageInput = document.getElementById('editProductImage');
            if (editImageInput) {
                editImageInput.addEventListener('change', function() {
                    handleImageUpload(this, 'editProductImagePreview', 'editProductImageName');
                });
            }
            
            // Add product image
            const addImageInput = document.getElementById('addProductImage');
            if (addImageInput) {
                addImageInput.addEventListener('change', function() {
                    handleImageUpload(this, 'addProductImagePreview', 'addProductImageName');
                });
            }
        });

        // Funciones para el modal de agregar producto
        let isAddingProduct = false;

        function openAddProductModal() {
            const modal = document.getElementById('addProductModal');
            modal.classList.add('show');
            // Limpiar formulario
            document.getElementById('addProductForm').reset();
            // Resetear estado
            isAddingProduct = false;
            const addButton = document.querySelector('#addProductModal .btn-primary');
            addButton.disabled = false;
            addButton.innerHTML = 'Agregar Producto';
        }

        function closeAddProductModal() {
            const modal = document.getElementById('addProductModal');
            modal.classList.remove('show');
        }

        async function addProduct() {
            // Prevenir múltiples clics
            if (isAddingProduct) {
                return;
            }

            const form = document.getElementById('addProductForm');
            const formData = new FormData(form);
            
            const productData = {
                name: formData.get('name'),
                category: formData.get('category'),
                price: parseFloat(formData.get('price')),
                status: formData.get('status'),
                image: formData.get('image') || null
            };

            // Validación básica
            if (!productData.name || !productData.category || !productData.price) {
                showErrorMessage('Por favor completa todos los campos requeridos');
                return;
            }

            // Deshabilitar botón y mostrar estado de carga
            isAddingProduct = true;
            const addButton = document.querySelector('#addProductModal .btn-primary');
            addButton.disabled = true;
            addButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Agregando...';

            try {
                const response = await fetch('{{ route("products.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(productData)
                });

                const result = await response.json();

                if (result.success) {
                    // Agregar el nuevo producto al array
                    currentProducts.push(result.product);
                    
                    // Guardar en cache
                    saveProductsToCache();
                    
                    // Agregar la nueva fila a la tabla
                    addProductToTable(result.product);
                    
                    // Cerrar modal
                    closeAddProductModal();
                    
                    // Mostrar mensaje de éxito
                    showSuccessMessage('Producto agregado: ' + result.product.name);
                } else {
                    // Mostrar errores específicos si existen
                    if (result.errors && result.errors.name) {
                        showErrorMessage('El nombre del producto ya existe');
                    } else {
                        showErrorMessage(result.message || 'Error al agregar el producto');
                    }
                }
            } catch (error) {
                console.error('Error adding product:', error);
                showErrorMessage('Error de conexión al agregar el producto');
            } finally {
                // Rehabilitar botón y resetear estado
                isAddingProduct = false;
                if (addButton) {
                    addButton.disabled = false;
                    addButton.innerHTML = 'Agregar Producto';
                }
            }
        }

        function addProductToTable(product) {
            const tableBody = document.querySelector('table tbody');
            
            // Mapear categoría a slug
            let categorySlug;
            switch(product.category) {
                case 'Bebidas Frías':
                    categorySlug = 'cold';
                    break;
                case 'Bebidas calientes':
                    categorySlug = 'hot';
                    break;
                case 'Tés e Infusiones':
                    categorySlug = 'tea';
                    break;
                case 'Repostería':
                    categorySlug = 'bakery';
                    break;
                default:
                    categorySlug = 'snacks';
            }
            
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="product-name">${product.name}</td>
                <td>
                    <div class="product-image-placeholder"></div>
                </td>
                <td>${product.category}</td>
                <td>$${parseFloat(product.price).toFixed(2)}</td>
                <td>
                    <span class="badge ${product.status === 'Activo' ? 'badge-success' : 'badge-danger'}">
                        ${product.status}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('products.show', ['category' => '__CATEGORY__', 'product' => '__ID__']) }}" class="btn-action view" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn-action edit" title="Editar" onclick="openEditModal(${product.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action delete" title="Eliminar" onclick="deleteProduct(${product.id}, '${product.name.replace(/'/g, "\\'")}')">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn-action toggle" title="${product.status === 'Activo' ? 'Desactivar' : 'Activar'}" onclick="toggleProductStatus(${product.id}, '${product.status}')">
                            <i class="fas fa-toggle-${product.status === 'Activo' ? 'on' : 'off'}"></i>
                        </button>
                    </div>
                </td>
            `;
            
            // Reemplazar placeholders con valores reales
            const viewLink = newRow.querySelector('a[href*="__CATEGORY__"]');
            if (viewLink) {
                viewLink.href = viewLink.href.replace('__CATEGORY__', categorySlug).replace('__ID__', product.id);
            }
            
            // Agregar la fila al principio de la tabla
            if (tableBody.firstChild) {
                tableBody.insertBefore(newRow, tableBody.firstChild);
            } else {
                tableBody.appendChild(newRow);
            }
            
            // Resaltar la nueva fila
            newRow.style.backgroundColor = '#d4edda';
            setTimeout(() => {
                newRow.style.backgroundColor = '';
            }, 3000);
        }

        // Cerrar modal al hacer clic fuera del contenido
        window.onclick = function(event) {
            const editModal = document.getElementById('editProductModal');
            const addModal = document.getElementById('addProductModal');
            
            if (event.target == editModal) {
                closeModal();
            }
            if (event.target == addModal) {
                closeAddProductModal();
            }
        }
    </script>
@endsection
