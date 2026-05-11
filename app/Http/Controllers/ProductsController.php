<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductsController extends Controller
{
    public function index()
    {
        // Get all product categories
        $categories = Product::whereNotNull('category')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

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

        return view('products.index', compact('allProducts', 'categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:products,name',
                'category' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'status' => 'required|in:Activo,Inactivo',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'image_name' => 'nullable|string|max:255',
            ]);

            $product = Product::query()->create([
                'name' => $request->name,
                'category' => $request->category,
                'price' => $request->price,
                'active' => $request->status === 'Activo',
            ]);

            // Manejar subida de imagen
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                
                // Crear registro en tabla de imágenes
                Image::create([
                    'path' => $imageName,
                    'imageable_type' => Product::class,
                    'imageable_id' => $product->id,
                ]);
            } elseif ($request->filled('image_name')) {
                // Si solo se proporciona el nombre, crear registro sin archivo
                Image::create([
                    'path' => $request->image_name,
                    'imageable_type' => Product::class,
                    'imageable_id' => $product->id,
                ]);
            }

            $product->load('images');

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
        // Mapeo de categorías
        $categoryMap = [
            'hot' => 'Bebidas calientes',
            'tea' => 'Tés e Infusiones',
            'bakery' => 'Repostería',
            'snacks' => 'Snacks',
            'cold' => 'Bebidas Frías'
        ];

        $categoryName = $categoryMap[$category] ?? $category;
        
        // Get all product categories
        $allCategories = Product::whereNotNull('category')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('category')
            ->toArray();
        
        // Obtener productos reales de la base de datos filtrados por categoría
        $products = Product::query()
            ->with('images')
            ->where('category', $categoryName)
            ->get()
            ->map(function ($product) {
                // Calcular estado basado en el campo active
                $status = $product->active ? 'Activo' : 'Inactivo';

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'category' => $product->category,
                    'price' => $product->price,
                    'status' => $status,
                ];
            });

        return view('products.category_products', compact('products', 'category', 'allCategories'));
    }

    public function show($category, $productId)
    {
        // Buscar el producto real en la base de datos
        $productModel = Product::find((int)$productId);

        if (!$productModel) {
            abort(404, 'Producto no encontrado');
        }

        // Transformar a array para la vista
        $product = [
            'id' => $productModel->id,
            'name' => $productModel->name,
            'image' => $productModel->image,
            'category' => $productModel->category,
            'price' => $productModel->price,
            'status' => $productModel->status,
            'description' => $productModel->description ?? 'Sin descripción',
            'recipe' => $productModel->name, // El nombre de la receta coincide con el producto
        ];

        return view('products.show', compact('product'));
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $request->validate([
                'name' => 'required|string|max:255|unique:products,name,' . $id,
                'category' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'status' => 'required|in:Activo,Inactivo',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'image_name' => 'nullable|string|max:255',
            ]);

            $updateData = [
                'name' => $request->name,
                'category' => $request->category,
                'price' => $request->price,
                'active' => $request->status === 'Activo',
            ];

            // Manejar subida de imagen
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);
                
                // Crear registro en tabla de imágenes
                $imageModel = Image::create([
                    'path' => $imageName,
                    'imageable_type' => Product::class,
                    'imageable_id' => $product->id,
                ]);
            } elseif ($request->filled('image_name')) {
                // Si solo se proporciona el nombre, crear registro sin archivo
                Image::create([
                    'path' => $request->image_name,
                    'imageable_type' => Product::class,
                    'imageable_id' => $product->id,
                ]);
            }

            $product->update($updateData);
            $product->load('images');

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado correctamente',
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
                'message' => 'Error al actualizar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el producto: ' . $e->getMessage()
            ], 500);
        }
    }
}
