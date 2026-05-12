@extends('layouts.app')

@section('title', $categoryName)
@section('page_title', $categoryName)

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

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 10px 0 0 0;
            font-size: 14px;
        }

        .breadcrumb-item {
            color: #666;
        }

        .breadcrumb-item.active {
            color: #5a7248;
            font-weight: 500;
        }

        .breadcrumb-item a {
            color: #5a7248;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline;
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

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
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
                padding: 15px 20px;
            }

            .page-title {
                font-size: 20px;
            }

            .table thead th,
            .table tbody td {
                padding: 10px;
                font-size: 13px;
            }

            .product-image {
                width: 40px;
                height: 40px;
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
        <div>
            <h1 class="page-title" style="margin: 0 0 15px 0;">{{ $categoryName }}</h1>
            <a href="{{ route('products.categories') }}" class="btn d-inline-flex align-items-center gap-2" style="background-color: #4a5d3a; border-color: #4a5d3a; color: white;">
                <i class="fas fa-arrow-left"></i>
                Volver a Categorías
            </a>
        </div>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('products.index') }}">Productos</a>
            </li>
            <li class="breadcrumb-item active">{{ $categoryName }}</li>
        </ol>
    </nav>

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
                @forelse($products as $product)
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
                                    $categorySlug = $product['category'] === 'Bebidas Frías' ? 'cold' : 
                                                   ($product['category'] === 'Bebidas calientes' ? 'hot' : 
                                                   ($product['category'] === 'Tés e Infusiones' ? 'tea' : 
                                                   ($product['category'] === 'Repostería' ? 'bakery' : 'snacks')));
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
                                <p class="text-muted">No hay productos en esta categoría</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
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
                                @foreach($allCategories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editProductPrice" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="editProductPrice" name="price" step="0.01" min="0" required>
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
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="saveProduct()">Guardar Cambios</button>
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

        .image-preview {
            width: 120px;
            height: 120px;
            max-width: 120px;
            max-height: 120px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #f8f9fa;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
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

        @media (max-width: 768px) {
            .modal-dialog {
                width: 95%;
                margin: 50px auto;
            }
        }
    </style>

    <script>
        // Cargar productos desde localStorage SOLO si existe cache válido para esta categoría específica
        let hasCache = false;
        let categorySlug = '{{ request()->segment(2) }}'; // Obtener el slug de la categoría desde la URL
        let cacheKey = 'products_category_' + categorySlug + '_cache'; // Cache específico para esta categoría
        let cachedProducts = loadProductsFromCache();
        let currentProducts;
        
        if (cachedProducts && cachedProducts.length > 0) {
            currentProducts = cachedProducts;
            hasCache = true;
            console.log('Using cached products for category', categorySlug + ':', currentProducts);
        } else {
            currentProducts = @json($products);
            console.log('Using original products for category', categorySlug + ':', currentProducts);
            // Limpiar cualquier cache residual de esta categoría
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
                console.log('Products saved to cache for category', categorySlug);
            } catch (e) {
                console.error('Error saving products to cache:', e);
            }
        }
        
        function clearProductsCache() {
            try {
                localStorage.removeItem(cacheKey);
                console.log('Products cache cleared for category', categorySlug);
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
                console.log('Product not found in currentProducts, reloading from original data...');
                const originalProducts = @json($products);
                product = originalProducts.find(p => p.id == productId);
                
                if (product) {
                    console.log('Product found in original data:', product);
                    // Agregar el producto de vuelta a currentProducts y actualizar el cache
                    currentProducts.push(product);
                    saveProductsToCache();
                    console.log('Product restored to currentProducts and cache updated');
                    
                    // Actualizar la tabla para mostrar el producto restaurado
                    restoreTableRow(product);
                }
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
            if (modal) {
                modal.classList.add('show');
                console.log('Modal opened successfully');
            } else {
                console.error('Modal not found!');
                alert('Error: No se encontró el modal del producto');
            }
        }

        function closeModal() {
            console.log('closeModal called');
            const modal = document.getElementById('editProductModal');
            console.log('modal element:', modal);
            modal.classList.remove('show');
        }

        function testModal() {
            console.log('testModal called');
            const modal = document.getElementById('editProductModal');
            console.log('modal element:', modal);
            if (modal) {
                modal.classList.add('show');
                console.log('modal show class added');
            }
        }

        function saveProduct() {
            const form = document.getElementById('editProductForm');
            const formData = new FormData(form);
            
            const productId = parseInt(formData.get('id'));
            const productData = {
                name: formData.get('name'),
                category: formData.get('category'),
                price: parseFloat(formData.get('price')),
                status: formData.get('status')
            };

            console.log('Saving product:', productId, productData);
            
            // Actualizar el producto en el array currentProducts
            const productIndex = currentProducts.findIndex(p => p.id == productId);
            if (productIndex !== -1) {
                currentProducts[productIndex] = { ...currentProducts[productIndex], ...productData };
                console.log('Updated product in array:', currentProducts[productIndex]);
                
                // Guardar en cache
                saveProductsToCache();
                
                // Actualizar la tabla en el DOM
                updateProductInTable(productId, productData);
                
                // Actualizar el botón de toggle si el estatus cambió
                updateToggleButton(productId, productData.status);
                
                // Mostrar mensaje de éxito
                showSuccessMessage('Producto actualizado: ' + productData.name);
            } else {
                console.error('Product not found in array');
                showErrorMessage('No se encontró el producto para actualizar');
            }
            
            closeModal();
        }

        function updateProductInTable(productId, productData) {
            // Encontrar la fila del producto en la tabla
            const tableRows = document.querySelectorAll('table tbody tr');
            tableRows.forEach(row => {
                const editButton = row.querySelector('button[onclick*="openEditModal"]');
                if (editButton) {
                    const onclickAttr = editButton.getAttribute('onclick');
                    const productIdMatch = onclickAttr.match(/openEditModal\((\d+)\)/);
                    
                    if (productIdMatch && parseInt(productIdMatch[1]) === productId) {
                        // Actualizar las celdas de la fila
                        const cells = row.querySelectorAll('td');
                        if (cells.length >= 6) {
                            cells[0].textContent = productData.name; // Nombre (columna 0)
                            // cells[1] es la imagen (no se actualiza)
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
                        return; // Stop searching after finding the row
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

        async function deleteProduct(productId, productName) {
            if (!confirm('¿Estás seguro de que quieres eliminar el producto "' + productName + '"?')) {
                return;
            }

            try {
                const response = await fetch('/products/' + productId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();

                if (result.success) {
                    const productIndex = currentProducts.findIndex(p => p.id == productId);
                    if (productIndex !== -1) {
                        currentProducts.splice(productIndex, 1);
                        saveProductsToCache();
                    }
                    removeTableRow(productId);
                    showSuccessMessage('Producto eliminado: ' + productName);
                } else {
                    showErrorMessage(result.message || 'Error al eliminar el producto');
                }
            } catch (error) {
                console.error('Error deleting product:', error);
                showErrorMessage('Error de conexión al eliminar el producto');
            }
        }

        function toggleProductStatus(productId, currentStatus) {
            console.log('=== TOGGLE FUNCTION CALLED ===');
            console.log('Toggling product:', productId, 'from', currentStatus);
            
            const newStatus = currentStatus === 'Activo' ? 'Inactivo' : 'Activo';
            console.log('New status will be:', newStatus);
            
            // Actualizar el producto en el array
            let productIndex = currentProducts.findIndex(p => p.id == productId);
            console.log('Product index found:', productIndex);
            
            if (productIndex === -1) {
                // Si no está en currentProducts, agregarlo primero desde los datos originales
                console.log('Product not found in currentProducts, loading from original data...');
                const originalProducts = @json($products);
                const originalProduct = originalProducts.find(p => p.id == productId);
                
                if (originalProduct) {
                    currentProducts.push(originalProduct);
                    productIndex = currentProducts.findIndex(p => p.id == productId);
                    console.log('Product restored from original data, new index:', productIndex);
                }
            }
            
            if (productIndex !== -1) {
                currentProducts[productIndex].status = newStatus;
                console.log('Product status updated in array:', currentProducts[productIndex]);
                
                // Guardar en cache
                saveProductsToCache();
                console.log('Cache saved after toggle');
                
                // Actualizar la tabla
                updateProductStatusInTable(productId, newStatus);
                
                // Actualizar el botón de toggle
                updateToggleButton(productId, newStatus);
                
                // Mostrar mensaje de éxito
                showSuccessMessage('Producto ' + (newStatus === 'Activo' ? 'activado' : 'desactivado') + ' correctamente');
            } else {
                console.error('Product not found for toggle. Available IDs:', currentProducts.map(p => p.id));
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

        function restoreTableRow(product) {
            console.log('Restoring table row for product:', product);
            
            // Buscar si la fila ya existe pero está oculta
            const tableRows = document.querySelectorAll('table tbody tr');
            for (let row of tableRows) {
                const editButton = row.querySelector('button[onclick*="openEditModal"]');
                if (editButton) {
                    const onclickAttr = editButton.getAttribute('onclick');
                    const productIdMatch = onclickAttr.match(/openEditModal\((\d+)\)/);
                    
                    if (productIdMatch && parseInt(productIdMatch[1]) === product.id) {
                        // La fila existe, restaurarla
                        console.log('Found existing row, restoring it');
                        row.style.display = '';
                        row.style.opacity = '1';
                        row.style.transform = 'translateX(0)';
                        row.style.backgroundColor = '#d4edda';
                        
                        setTimeout(() => {
                            row.style.backgroundColor = '';
                        }, 2000);
                        return;
                    }
                }
            }
            
            // Si no se encuentra la fila, mostrar mensaje indicando recargar página
            console.log('Row not found, showing restore message');
            showSuccessMessage('Producto restaurado: ' + product.name + '. Recarga la página para verlo en la tabla.');
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
                                const cachedProduct = currentProducts.find(p => p.id === productId);
                                
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
                                }
                            }
                        }
                    }
                });
            }
        }

        // Hacer funciones accesibles globalmente
        window.openEditModal = openEditModal;
        window.deleteProduct = deleteProduct;
        window.toggleProductStatus = toggleProductStatus;
        window.closeModal = closeModal;
        window.saveProduct = saveProduct;

        // Inicializar la tabla cuando la página carga
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing...');
            console.log('Functions available:', {
                openEditModal: typeof window.openEditModal,
                deleteProduct: typeof window.deleteProduct,
                toggleProductStatus: typeof window.toggleProductStatus
            });
            
            setTimeout(rebuildTableFromCache, 100); // Pequeño delay para asegurar que el DOM está listo
        });

        // Cerrar modal al hacer clic fuera del contenido
        window.onclick = function(event) {
            const modal = document.getElementById('editProductModal');
            if (event.target == modal) {
                closeModal();
            }
        }
        
        console.log('Script loaded successfully');
    </script>
@endsection
