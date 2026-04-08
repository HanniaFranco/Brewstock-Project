<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $allProducts = Product::query()->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'category' => $product->category,
                'price' => $product->price,
                'status' => $product->status,
            ];
        })->toArray();

        return view('products.index', compact('allProducts'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:products,name',
                'category' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'status' => 'required|in:Activo,Inactivo',
                'image' => 'nullable|string|max:255',
            ]);

            $product = Product::query()->create([
                'name' => $request->name,
                'category' => $request->category,
                'price' => $request->price,
                'status' => $request->status,
                'image' => $request->image ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Producto agregado correctamente',
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'category' => $product->category,
                    'price' => $product->price,
                    'status' => $product->status,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function categories()
    {
        $categories = [
            [
                'id' => 'hot',
                'name' => 'Bebidas calientes',
                'icon' => 'fa-mug-hot',
                'filter_group' => 'hot'
            ],
            [
                'id' => 'tea',
                'name' => 'Tés e Infusiones',
                'icon' => 'fa-leaf',
                'filter_group' => 'tea'
            ],
            [
                'id' => 'bakery',
                'name' => 'Repostería',
                'icon' => 'fa-birthday-cake',
                'filter_group' => 'bakery'
            ],
            [
                'id' => 'snacks',
                'name' => 'Snacks',
                'icon' => 'fa-apple-alt',
                'filter_group' => 'snacks'
            ],
            [
                'id' => 'cold',
                'name' => 'Bebidas Frías',
                'icon' => 'fa-glass-water',
                'filter_group' => 'cold'
            ]
        ];

        return view('products.categories', compact('categories'));
    }

    public function showCategoryProducts($category)
    {
        // Datos de ejemplo para productos
        $allProducts = [
            ['id' => 1, 'name' => 'Iced Americano', 'image' => 'iced_americano.jpg', 'category' => 'Bebidas Frías', 'price' => 24.00, 'status' => 'Activo'],
            ['id' => 2, 'name' => 'Iced Latte', 'image' => 'iced_latte.jpg', 'category' => 'Bebidas Frías', 'price' => 24.00, 'status' => 'Activo'],
            ['id' => 3, 'name' => 'Frappé de Café', 'image' => 'frappe_cafe.jpg', 'category' => 'Bebidas Frías', 'price' => 24.00, 'status' => 'Activo'],
            ['id' => 4, 'name' => 'Iced Matcha Latte', 'image' => 'iced_matcha_latte.jpg', 'category' => 'Bebidas Frías', 'price' => 24.00, 'status' => 'Activo'],
            ['id' => 5, 'name' => 'Café Americano', 'image' => 'americano.jpg', 'category' => 'Bebidas calientes', 'price' => 20.00, 'status' => 'Activo'],
            ['id' => 6, 'name' => 'Café Latte', 'image' => 'latte.jpg', 'category' => 'Bebidas calientes', 'price' => 22.00, 'status' => 'Activo'],
            ['id' => 7, 'name' => 'Cappuccino', 'image' => 'cappuccino.jpg', 'category' => 'Bebidas calientes', 'price' => 22.00, 'status' => 'Activo'],
            ['id' => 8, 'name' => 'Té Verde', 'image' => 'te_verde.jpg', 'category' => 'Tés e Infusiones', 'price' => 18.00, 'status' => 'Activo'],
            ['id' => 9, 'name' => 'Té Negro', 'image' => 'te_negro.jpg', 'category' => 'Tés e Infusiones', 'price' => 18.00, 'status' => 'Activo'],
            ['id' => 10, 'name' => 'Croissant', 'image' => 'croissant.jpg', 'category' => 'Repostería', 'price' => 15.00, 'status' => 'Activo'],
            ['id' => 11, 'name' => 'Muffin', 'image' => 'muffin.jpg', 'category' => 'Repostería', 'price' => 12.00, 'status' => 'Activo'],
            ['id' => 12, 'name' => 'Mix de Frutos Secos', 'image' => 'frutos_secos.jpg', 'category' => 'Snacks', 'price' => 25.00, 'status' => 'Activo'],
        ];

        // Mapeo de categorías
        $categoryMap = [
            'hot' => 'Bebidas calientes',
            'tea' => 'Tés e Infusiones',
            'bakery' => 'Repostería',
            'snacks' => 'Snacks',
            'cold' => 'Bebidas Frías'
        ];

        $categoryName = $categoryMap[$category] ?? $category;
        
        // Filtrar productos por categoría
        $filteredProducts = array_filter($allProducts, function ($product) use ($categoryName) {
            return $product['category'] === $categoryName;
        });

        return view('products.category_products', [
            'categoryName' => $categoryName,
            'products' => $filteredProducts
        ]);
    }

    public function show($category, $product)
    {
        // Datos de ejemplo para todos los productos
        $allProducts = [
            ['id' => 1, 'name' => 'Iced Americano', 'image' => 'iced_americano.jpg', 'category' => 'Bebidas Frías', 'price' => 24.00, 'status' => 'Activo', 'description' => 'Café americano servido frío con hielo', 'recipe' => 'Iced Americano'],
            ['id' => 2, 'name' => 'Iced Latte', 'image' => 'iced_latte.jpg', 'category' => 'Bebidas Frías', 'price' => 24.00, 'status' => 'Activo', 'description' => 'Latte servido frío con leche y hielo', 'recipe' => 'Iced Latte'],
            ['id' => 3, 'name' => 'Frappé de Café', 'image' => 'frappe_cafe.jpg', 'category' => 'Bebidas Frías', 'price' => 24.00, 'status' => 'Activo', 'description' => 'Bebida helada de café batido', 'recipe' => 'Frappé de Café'],
            ['id' => 4, 'name' => 'Iced Matcha Latte', 'image' => 'iced_matcha_latte.jpg', 'category' => 'Bebidas Frías', 'price' => 24.00, 'status' => 'Activo', 'description' => 'Latte de té verde matcha servido frío', 'recipe' => 'Iced Matcha Latte'],
            ['id' => 5, 'name' => 'Café Americano', 'image' => 'americano.jpg', 'category' => 'Bebidas calientes', 'price' => 20.00, 'status' => 'Activo', 'description' => 'Café negro clásico', 'recipe' => 'Café Americano'],
            ['id' => 6, 'name' => 'Café Latte', 'image' => 'latte.jpg', 'category' => 'Bebidas calientes', 'price' => 22.00, 'status' => 'Activo', 'description' => 'Café con leche vaporizada', 'recipe' => 'Café Latte'],
            ['id' => 7, 'name' => 'Cappuccino', 'image' => 'cappuccino.jpg', 'category' => 'Bebidas calientes', 'price' => 22.00, 'status' => 'Activo', 'description' => 'Café con leche espuma y cacao', 'recipe' => 'Cappuccino'],
            ['id' => 8, 'name' => 'Té Verde', 'image' => 'te_verde.jpg', 'category' => 'Tés e Infusiones', 'price' => 18.00, 'status' => 'Activo', 'description' => 'Té verde japonés tradicional', 'recipe' => 'Té Verde'],
            ['id' => 9, 'name' => 'Té Negro', 'image' => 'te_negro.jpg', 'category' => 'Tés e Infusiones', 'price' => 18.00, 'status' => 'Activo', 'description' => 'Té negro clásico', 'recipe' => 'Té Negro'],
            ['id' => 10, 'name' => 'Croissant', 'image' => 'croissant.jpg', 'category' => 'Repostería', 'price' => 15.00, 'status' => 'Activo', 'description' => 'Panecillo francés hojaldrado', 'recipe' => 'Croissant'],
            ['id' => 11, 'name' => 'Muffin', 'image' => 'muffin.jpg', 'category' => 'Repostería', 'price' => 12.00, 'status' => 'Activo', 'description' => 'Pastelito individual de vainilla', 'recipe' => 'Muffin'],
            ['id' => 12, 'name' => 'Mix de Frutos Secos', 'image' => 'frutos_secos.jpg', 'category' => 'Snacks', 'price' => 25.00, 'status' => 'Activo', 'description' => 'Mezcla de nueces y frutas deshidratadas', 'recipe' => 'Mix de Frutos Secos'],
        ];

        // Buscar el producto por ID
        $product = collect($allProducts)->firstWhere('id', (int)$product);

        if (!$product) {
            abort(404, 'Producto no encontrado');
        }

        return view('products.show', compact('product'));
    }
}
