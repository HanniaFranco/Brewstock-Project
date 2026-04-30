<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\AlertsController;
use App\Http\Controllers\LogsController;

Route::get('/logo.png', function () {
    $candidates = [
        public_path('logo.png'),
        base_path('resources/images/logo.png'),
    ];

    foreach ($candidates as $path) {
        if (is_file($path)) {
            return response()->file($path, [
                'Content-Type' => 'image/png',
            ]);
        }
    }

    abort(404);
});

Route::get('/__debug/paths', function () {
    return response()->json([
        'base_path' => base_path(),
        'public_path' => public_path(),
        'logo_public' => [
            'path' => public_path('logo.png'),
            'exists' => is_file(public_path('logo.png')),
        ],
        'logo_resources' => [
            'path' => base_path('resources/images/logo.png'),
            'exists' => is_file(base_path('resources/images/logo.png')),
        ],
        'app_url' => config('app.url'),
    ]);
});

// Login Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request')->middleware('guest');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('guest');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('guest');

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    

    Route::get('/logs', [LogsController::class, 'index'])->name('logs.index');
    Route::delete('/logs/clear', [LogsController::class, 'clear'])->name('logs.clear');

    // Products routes
    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    Route::get('/products/categories', [ProductsController::class, 'categories'])->name('products.categories');
    Route::get('/products/{category}', [ProductsController::class, 'showCategoryProducts'])->name('products.category');
    Route::get('/products/{category}/{product}', [ProductsController::class, 'show'])->name('products.show');
    
    // Inventory routes
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/ingredients', [InventoryController::class, 'ingredients'])->name('inventory.ingredients');
    Route::post('/inventory/ingredients', [InventoryController::class, 'storeIngredient'])->name('inventory.ingredients.store');
    Route::get('/inventory/recipes', [InventoryController::class, 'recipes'])->name('inventory.recipes');
    Route::get('/inventory/recipes/create', [InventoryController::class, 'createRecipe'])->name('inventory.recipes.create');
    Route::get('/inventory/recipes/{slug}', [InventoryController::class, 'showRecipe'])->name('inventory.recipes.show');
    Route::post('/inventory/recipes', [InventoryController::class, 'storeRecipe'])->name('inventory.recipes.store');
    Route::put('/inventory/recipes/{slug}', [InventoryController::class, 'updateRecipe'])->name('inventory.recipes.update');
    
    // Users routes
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update');
    
    // Sales routes
    Route::middleware('restrict.sales')->group(function () {
        Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    });
    
    // Alerts routes
    Route::middleware('restrict.alerts')->group(function () {
        Route::get('/alerts', [AlertsController::class, 'index'])->name('alerts.index');
        Route::get('/alerts/settings', [AlertsController::class, 'settings'])->name('alerts.settings');
        Route::post('/alerts/settings', [AlertsController::class, 'store'])->name('alerts.settings.store');
        Route::get('/alerts/unread', [AlertsController::class, 'unread'])->name('alerts.unread');
        Route::post('/alerts/{id}/read', [AlertsController::class, 'markRead'])->name('alerts.read');
    });

    // Admin users management routes
    Route::middleware('admin')->group(function () {
        Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
        Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    });
    
});

// Redirect root to dashboard if authenticated, otherwise to login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});
