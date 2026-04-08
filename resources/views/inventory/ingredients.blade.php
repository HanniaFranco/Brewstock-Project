@extends('layouts.app')

@section('title', 'Ingredientes')
@section('page_title', 'Todos los Ingredientes')

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

        .add-ingredient-btn {
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

        .add-ingredient-btn:hover {
            background: #4a5d3a;
            transform: translateY(-1px);
        }

        .ingredients-table {
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

        .ingredient-name {
            font-weight: 500;
            color: #333;
        }

        .stock-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .stock-ok {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .low-stock {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .critical-stock {
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

        .expiration-date {
            font-size: 13px;
            color: #666;
        }

        .expiration-date.expired {
            color: #dc3545;
            font-weight: 500;
        }

        .expiration-date.near-expiry {
            color: #856404;
            font-weight: 500;
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
        <h1 class="page-title">Todos los Ingredientes</h1>
        <button class="add-ingredient-btn" onclick="openAddIngredientModal()">
            <i class="fas fa-plus"></i>
            Agregar Ingrediente
        </button>
    </div>

    <!-- Ingredients Table -->
    <div class="ingredients-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Unidad</th>
                    <th>Stock Actual</th>
                    <th>Stock Mínimo</th>
                    <th>Costo por Unidad</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Estatus</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allIngredients ?? $ingredients as $ingredient)
                    <tr>
                        <td class="ingredient-name">{{ $ingredient['name'] }}</td>
                        <td>{{ $ingredient['unit'] }}</td>
                        <td>{{ number_format($ingredient['current_stock'], 2) }}</td>
                        <td>{{ number_format($ingredient['minimum_stock'], 2) }}</td>
                        <td>${{ number_format($ingredient['cost_per_unit'], 2) }}</td>
                        <td>
                            @if($ingredient['expiration_date'])
                                <span class="expiration-date 
                                    @if(now()->gt(\Carbon\Carbon::parse($ingredient['expiration_date'])))
                                        expired
                                    @elseif(now()->diffInDays(\Carbon\Carbon::parse($ingredient['expiration_date'])) <= 7)
                                        near-expiry
                                    @endif">
                                    {{ \Carbon\Carbon::parse($ingredient['expiration_date'])->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="expiration-date">Sin vencimiento</span>
                            @endif
                        </td>
                        <td>
                            <span class="stock-badge 
                                @switch($ingredient['status'])
                                    @case('stock_ok')
                                        stock-ok
                                    @break
                                    @case('low_stock')
                                        low-stock
                                    @break
                                    @case('critical_stock')
                                        critical-stock
                                    @break
                                @endswitch">
                                @switch($ingredient['status'])
                                    @case('stock_ok')
                                        Stock OK
                                    @break
                                    @case('low_stock')
                                        Stock Bajo
                                    @break
                                    @case('critical_stock')
                                        Stock Crítico
                                    @break
                                @endswitch
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action edit" title="Editar" onclick="openEditIngredientModal({{ $ingredient['id'] }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action delete" title="Eliminar" onclick="deleteIngredient({{ $ingredient['id'] }}, '{{ $ingredient['name'] }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="no-ingredients">
                                <i class="fas fa-flask fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No hay ingredientes registrados</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Ingredient Modal -->
    <div class="modal fade" id="addIngredientModal" tabindex="-1" aria-labelledby="addIngredientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addIngredientModalLabel">Agregar Nuevo Ingrediente</h5>
                    <button type="button" class="btn-close" onclick="closeAddIngredientModal()" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addIngredientForm">
                        <div class="form-group">
                            <label for="addIngredientName" class="form-label">Nombre del ingrediente</label>
                            <input type="text" class="form-control" id="addIngredientName" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="addIngredientUnit" class="form-label">Unidad</label>
                            <select class="form-control" id="addIngredientUnit" name="unit" required>
                                <option value="">Seleccionar unidad</option>
                                <option value="kg">Kilogramos (kg)</option>
                                <option value="g">Gramos (g)</option>
                                <option value="L">Litros (L)</option>
                                <option value="mL">Mililitros (mL)</option>
                                <option value="unidades">Unidades</option>
                                <option value="cajas">Cajas</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="addIngredientCurrentStock" class="form-label">Stock Actual</label>
                            <input type="number" class="form-control" id="addIngredientCurrentStock" name="current_stock" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="addIngredientMinimumStock" class="form-label">Stock Mínimo</label>
                            <input type="number" class="form-control" id="addIngredientMinimumStock" name="minimum_stock" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="addIngredientCost" class="form-label">Costo por Unidad</label>
                            <input type="number" class="form-control" id="addIngredientCost" name="cost_per_unit" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="addIngredientExpiration" class="form-label">Fecha de Vencimiento (opcional)</label>
                            <input type="date" class="form-control" id="addIngredientExpiration" name="expiration_date">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddIngredientModal()">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="addIngredient()">Agregar Ingrediente</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Ingredient Modal -->
    <div class="modal fade" id="editIngredientModal" tabindex="-1" aria-labelledby="editIngredientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editIngredientModalLabel">Editar Ingrediente</h5>
                    <button type="button" class="btn-close" onclick="closeEditIngredientModal()" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editIngredientForm">
                        <input type="hidden" id="editIngredientId" name="id">
                        
                        <div class="form-group">
                            <label for="editIngredientName" class="form-label">Nombre del ingrediente</label>
                            <input type="text" class="form-control" id="editIngredientName" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="editIngredientUnit" class="form-label">Unidad</label>
                            <select class="form-control" id="editIngredientUnit" name="unit" required>
                                <option value="">Seleccionar unidad</option>
                                <option value="kg">Kilogramos (kg)</option>
                                <option value="g">Gramos (g)</option>
                                <option value="L">Litros (L)</option>
                                <option value="mL">Mililitros (mL)</option>
                                <option value="unidades">Unidades</option>
                                <option value="cajas">Cajas</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editIngredientCurrentStock" class="form-label">Stock Actual</label>
                            <input type="number" class="form-control" id="editIngredientCurrentStock" name="current_stock" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="editIngredientMinimumStock" class="form-label">Stock Mínimo</label>
                            <input type="number" class="form-control" id="editIngredientMinimumStock" name="minimum_stock" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="editIngredientCost" class="form-label">Costo por Unidad</label>
                            <input type="number" class="form-control" id="editIngredientCost" name="cost_per_unit" step="0.01" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="editIngredientExpiration" class="form-label">Fecha de Vencimiento (opcional)</label>
                            <input type="date" class="form-control" id="editIngredientExpiration" name="expiration_date">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditIngredientModal()">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="updateIngredient()">Guardar Cambios</button>
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
    </style>

    <script>
        // Cargar ingredientes desde localStorage
        let hasCache = false;
        let cacheKey = 'ingredients_cache';
        let cachedIngredients = loadIngredientsFromCache();
        let currentIngredients;
        
        if (cachedIngredients && cachedIngredients.length > 0) {
            currentIngredients = cachedIngredients;
            hasCache = true;
            console.log('Using cached ingredients:', currentIngredients);
        } else {
            currentIngredients = @json($allIngredients ?? $ingredients);
            console.log('Using original ingredients:', currentIngredients);
            clearIngredientsCache();
        }
        
        function loadIngredientsFromCache() {
            try {
                const cached = localStorage.getItem(cacheKey);
                return cached ? JSON.parse(cached) : null;
            } catch (e) {
                console.error('Error loading ingredients from cache:', e);
                return null;
            }
        }
        
        function saveIngredientsToCache() {
            try {
                localStorage.setItem(cacheKey, JSON.stringify(currentIngredients));
                console.log('Ingredients saved to cache');
            } catch (e) {
                console.error('Error saving ingredients to cache:', e);
            }
        }
        
        function clearIngredientsCache() {
            try {
                localStorage.removeItem(cacheKey);
                console.log('Ingredients cache cleared');
            } catch (e) {
                console.error('Error clearing ingredients cache:', e);
            }
        }

        // Funciones para el modal de agregar ingrediente
        let isAddingIngredient = false;

        function openAddIngredientModal() {
            const modal = document.getElementById('addIngredientModal');
            modal.classList.add('show');
            document.getElementById('addIngredientForm').reset();
            isAddingIngredient = false;
            const addButton = document.querySelector('#addIngredientModal .btn-primary');
            addButton.disabled = false;
            addButton.innerHTML = 'Agregar Ingrediente';
        }

        function openEditIngredientModal(ingredientId) {
            const ingredient = currentIngredients.find(i => i.id == ingredientId);
            if (!ingredient) return;

            // Rellenar el formulario con los datos del ingrediente
            document.getElementById('editIngredientId').value = ingredient.id;
            document.getElementById('editIngredientName').value = ingredient.name;
            document.getElementById('editIngredientUnit').value = ingredient.unit;
            document.getElementById('editIngredientCurrentStock').value = ingredient.current_stock;
            document.getElementById('editIngredientMinimumStock').value = ingredient.minimum_stock;
            document.getElementById('editIngredientCost').value = ingredient.cost_per_unit;
            document.getElementById('editIngredientExpiration').value = ingredient.expiration_date || '';

            // Mostrar el modal
            const modal = document.getElementById('editIngredientModal');
            modal.classList.add('show');
        }

        function closeAddIngredientModal() {
            const modal = document.getElementById('addIngredientModal');
            modal.classList.remove('show');
        }

        async function addIngredient() {
            if (isAddingIngredient) {
                return;
            }

            const form = document.getElementById('addIngredientForm');
            const formData = new FormData(form);
            
            const ingredientData = {
                name: formData.get('name'),
                unit: formData.get('unit'),
                current_stock: parseFloat(formData.get('current_stock')),
                minimum_stock: parseFloat(formData.get('minimum_stock')),
                cost_per_unit: parseFloat(formData.get('cost_per_unit')),
                expiration_date: formData.get('expiration_date') || null,
            };

            if (!ingredientData.name || !ingredientData.unit || !ingredientData.current_stock || !ingredientData.minimum_stock || !ingredientData.cost_per_unit) {
                showErrorMessage('Por favor completa todos los campos requeridos');
                return;
            }

            isAddingIngredient = true;
            const addButton = document.querySelector('#addIngredientModal .btn-primary');
            addButton.disabled = true;
            addButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Agregando...';

            try {
                const response = await fetch('{{ route("inventory.ingredients.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(ingredientData)
                });

                const result = await response.json();

                if (result.success) {
                    currentIngredients.push(result.ingredient);
                    saveIngredientsToCache();
                    addIngredientToTable(result.ingredient);
                    closeAddIngredientModal();
                    showSuccessMessage('Ingrediente agregado: ' + result.ingredient.name);
                } else {
                    showErrorMessage(result.message || 'Error al agregar el ingrediente');
                }
            } catch (error) {
                console.error('Error adding ingredient:', error);
                showErrorMessage('Error de conexión al agregar el ingrediente');
            } finally {
                isAddingIngredient = false;
                if (addButton) {
                    addButton.disabled = false;
                    addButton.innerHTML = 'Agregar Ingrediente';
                }
            }
        }

        function addIngredientToTable(ingredient) {
            const tableBody = document.querySelector('table tbody');
            
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td class="ingredient-name">${ingredient.name}</td>
                <td>${ingredient.unit}</td>
                <td>${ingredient.current_stock.toFixed(2)}</td>
                <td>${ingredient.minimum_stock.toFixed(2)}</td>
                <td>$${ingredient.cost_per_unit.toFixed(2)}</td>
                <td>
                    ${ingredient.expiration_date ? 
                        `<span class="expiration-date">${new Date(ingredient.expiration_date).toLocaleDateString('es-ES')}</span>` : 
                        '<span class="expiration-date">Sin vencimiento</span>'}
                </td>
                <td>
                    <span class="stock-badge ${ingredient.status === 'stock_ok' ? 'stock-ok' : ingredient.status === 'low_stock' ? 'low-stock' : 'critical-stock'}">
                        ${ingredient.status === 'stock_ok' ? 'Stock OK' : ingredient.status === 'low_stock' ? 'Stock Bajo' : 'Stock Crítico'}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action edit" title="Editar" onclick="openEditIngredientModal(${ingredient.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action delete" title="Eliminar" onclick="deleteIngredient(${ingredient.id}, '${ingredient.name.replace(/'/g, "\\'")}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            
            if (tableBody.firstChild) {
                tableBody.insertBefore(newRow, tableBody.firstChild);
            } else {
                tableBody.appendChild(newRow);
            }
            
            newRow.style.backgroundColor = '#d4edda';
            setTimeout(() => {
                newRow.style.backgroundColor = '';
            }, 3000);
        }

        function deleteIngredient(ingredientId, ingredientName) {
            if (confirm('¿Estás seguro de que quieres eliminar el ingrediente "' + ingredientName + '"?')) {
                const ingredientIndex = currentIngredients.findIndex(i => i.id == ingredientId);
                
                if (ingredientIndex !== -1) {
                    currentIngredients.splice(ingredientIndex, 1);
                    saveIngredientsToCache();
                    removeIngredientTableRow(ingredientId);
                    showSuccessMessage('Ingrediente eliminado: ' + ingredientName);
                }
            }
        }

        function removeIngredientTableRow(ingredientId) {
            const tableRows = document.querySelectorAll('table tbody tr');
            tableRows.forEach(row => {
                const deleteButton = row.querySelector('button[onclick*="deleteIngredient"]');
                if (deleteButton) {
                    const onclickAttr = deleteButton.getAttribute('onclick');
                    const ingredientIdMatch = onclickAttr.match(/deleteIngredient\((\d+)/);
                    
                    if (ingredientIdMatch && parseInt(ingredientIdMatch[1]) === ingredientId) {
                        row.style.backgroundColor = '#f8d7da';
                        row.style.transition = 'all 0.3s ease';
                        
                        setTimeout(() => {
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(-20px)';
                            
                            setTimeout(() => {
                                row.remove();
                            }, 300);
                        }, 500);
                        return;
                    }
                }
            });
        }

        function showSuccessMessage(message) {
            const toast = document.createElement('div');
            toast.className = 'alert alert-success position-fixed top-0 end-0 m-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                ${message}
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        function showErrorMessage(message) {
            const toast = document.createElement('div');
            toast.className = 'alert alert-danger position-fixed top-0 end-0 m-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>
                ${message}
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        function closeEditIngredientModal() {
            const modal = document.getElementById('editIngredientModal');
            modal.classList.remove('show');
        }

        async function updateIngredient() {
            const form = document.getElementById('editIngredientForm');
            const formData = new FormData(form);
            
            const ingredientData = {
                id: parseInt(formData.get('id')),
                name: formData.get('name'),
                unit: formData.get('unit'),
                current_stock: parseFloat(formData.get('current_stock')),
                minimum_stock: parseFloat(formData.get('minimum_stock')),
                cost_per_unit: parseFloat(formData.get('cost_per_unit')),
                expiration_date: formData.get('expiration_date') || null,
            };

            if (!ingredientData.name || !ingredientData.unit || !ingredientData.current_stock || !ingredientData.minimum_stock || !ingredientData.cost_per_unit) {
                showErrorMessage('Por favor completa todos los campos requeridos');
                return;
            }

            // Calcular estado basado en el stock
            const currentStock = ingredientData.current_stock;
            const minimumStock = ingredientData.minimum_stock;
            
            if (currentStock == 0) {
                ingredientData.status = 'critical_stock';
            } else if (currentStock < minimumStock) {
                ingredientData.status = 'low_stock';
            } else {
                ingredientData.status = 'stock_ok';
            }

            try {
                // Simular actualización (reemplazar con llamada real al backend)
                const ingredientIndex = currentIngredients.findIndex(i => i.id == ingredientData.id);
                if (ingredientIndex !== -1) {
                    currentIngredients[ingredientIndex] = { ...currentIngredients[ingredientIndex], ...ingredientData };
                    saveIngredientsToCache();
                    updateIngredientInTable(ingredientData.id, ingredientData);
                    closeEditIngredientModal();
                    showSuccessMessage('Ingrediente actualizado: ' + ingredientData.name);
                } else {
                    showErrorMessage('No se encontró el ingrediente para actualizar');
                }
            } catch (error) {
                console.error('Error updating ingredient:', error);
                showErrorMessage('Error de conexión al actualizar el ingrediente');
            }
        }

        function updateIngredientInTable(ingredientId, ingredientData) {
            const tableRows = document.querySelectorAll('table tbody tr');
            tableRows.forEach(row => {
                const editButton = row.querySelector('button[onclick*="openEditIngredientModal"]');
                if (editButton) {
                    const onclickAttr = editButton.getAttribute('onclick');
                    const ingredientIdMatch = onclickAttr.match(/openEditIngredientModal\((\d+)/);
                    
                    if (ingredientIdMatch && parseInt(ingredientIdMatch[1]) === ingredientId) {
                        const cells = row.querySelectorAll('td');
                        if (cells.length >= 7) {
                            cells[0].textContent = ingredientData.name; // Nombre
                            cells[1].textContent = ingredientData.unit; // Unidad
                            cells[2].textContent = ingredientData.current_stock.toFixed(2); // Stock Actual
                            cells[3].textContent = ingredientData.minimum_stock.toFixed(2); // Stock Mínimo
                            cells[4].textContent = '$' + ingredientData.cost_per_unit.toFixed(2); // Costo
                            
                            // Actualizar fecha de vencimiento
                            const dateCell = cells[5];
                            if (ingredientData.expiration_date) {
                                dateCell.innerHTML = `<span class="expiration-date">${new Date(ingredientData.expiration_date).toLocaleDateString('es-ES')}</span>`;
                            } else {
                                dateCell.innerHTML = '<span class="expiration-date">Sin vencimiento</span>';
                            }
                            
                            // Actualizar badge de estatus
                            const statusBadge = cells[6].querySelector('.stock-badge');
                            if (statusBadge) {
                                statusBadge.textContent = ingredientData.status === 'stock_ok' ? 'Stock OK' : ingredientData.status === 'low_stock' ? 'Stock Bajo' : 'Stock Crítico';
                                statusBadge.className = 'stock-badge ' + (ingredientData.status === 'stock_ok' ? 'stock-ok' : ingredientData.status === 'low_stock' ? 'low-stock' : 'critical-stock');
                            }
                            
                            // Actualizar botón de editar
                            editButton.setAttribute('onclick', 'openEditIngredientModal(' + ingredientId + ')');
                            
                            // Resaltar la fila
                            row.style.backgroundColor = '#d4edda';
                            setTimeout(() => {
                                row.style.backgroundColor = '';
                            }, 2000);
                        }
                        return;
                    }
                }
            });
        }

        // Cerrar modal al hacer clic fuera del contenido
        window.onclick = function(event) {
            const addModal = document.getElementById('addIngredientModal');
            const editModal = document.getElementById('editIngredientModal');
            
            if (event.target == addModal) {
                closeAddIngredientModal();
            }
            if (event.target == editModal) {
                closeEditIngredientModal();
            }
        }
    </script>
@endsection
