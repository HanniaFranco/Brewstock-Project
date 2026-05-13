<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Image;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Services\RecipeRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    public function index()
    {
        // Get ingredient categories with counts (excluding temporary ingredients)
        $ingredientCategories = Ingredient::whereNotNull('category')
            ->where('name', 'NOT LIKE', '_temp_%')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        // Add ingredients without category (excluding temporary ingredients)
        $ingredientsWithoutCategory = Ingredient::whereNull('category')
            ->where('name', 'NOT LIKE', '_temp_%')
            ->count();
        
        return view('inventory.index', compact('ingredientCategories', 'ingredientsWithoutCategory'));
    }

    public function ingredients(Request $request)
    {
        // Get all ingredient categories
        $categories = Ingredient::whereNotNull('category')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        $query = Ingredient::with('images');
        
        // Filter by category if provided
        if ($request->has('category') && $request->category !== 'sin-categoria') {
            $categoryName = str_replace('-', ' ', $request->category);
            $query->where('category', $categoryName);
        } elseif ($request->category === 'sin-categoria') {
            $query->whereNull('category');
        }
        
        $ingredients = $query->get()->filter(function ($ingredient) {
            // Filter out temporary ingredients used for categories
            return !str_starts_with($ingredient->name, '_temp_');
        })->map(function ($ingredient) {
            // Calcular estado basado en el stock
            if ($ingredient->current_stock == 0) {
                $status = 'critical_stock';
            } elseif ($ingredient->current_stock < $ingredient->minimum_stock) {
                $status = 'low_stock';
            } else {
                $status = 'stock_ok';
            }

            return [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'category' => $ingredient->category ?? 'Sin categoría',
                'unit' => $ingredient->unit ?? 'kg', // Default si no tiene unidad
                'current_stock' => (float) $ingredient->current_stock,
                'minimum_stock' => (float) $ingredient->minimum_stock,
                'cost_per_unit' => (float) ($ingredient->cost_per_unit ?? 0),
                'expiration_date' => $ingredient->expiration_date,
                'status' => $status,
                'image' => $ingredient->image, // Agregar la imagen desde la relación
            ];
        });

        Log::info('Ingredients data being passed to view:', $ingredients->toArray());
        Log::info('Ingredients count:', ['count' => $ingredients->count()]);
        return view('inventory.ingredients', ['allIngredients' => $ingredients->values()->toArray(), 'categories' => $categories]);
    }

    public function storeIngredient(Request $request)
{
    try {
        // Debug: Log all incoming data
        \Log::info('StoreIngredient request data:', $request->all());
        
        $ingredientId = $request->id ?? null;
        $nameRule = $ingredientId
            ? 'required|string|max:255|unique:ingredients,name,' . $ingredientId
            : 'required|string|max:255|unique:ingredients,name';

        // Temporarily simplified validation to debug
        $rules = [
            'name'            => $nameRule,
            'category'        => 'nullable|string|max:255',
            'unit'            => 'required|string|max:50',
            'current_stock'   => 'required|numeric|min:0',
            'minimum_stock'   => 'required|numeric|min:0',
            'cost_per_unit'   => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_name'      => 'nullable|string|max:255',
        ];
        
        Log::info('Validation rules:', $rules);
        Log::info('Request data for validation:', $request->all());
        
        $request->validate($rules);

        // ✅ Properly separated status calculation
        $currentStock = $request->current_stock;
        $minimumStock = $request->minimum_stock;

        if ($currentStock == 0) {
            $status = 'critical_stock';
        } elseif ($currentStock < $minimumStock) {
            $status = 'low_stock';
        } else {
            $status = 'stock_ok'; // ✅ was missing
        }

        $imageHandler = function (Ingredient $ingredient) use ($request) {
            // If a new file is uploaded, replace existing image file & record
            if ($request->hasFile('image')) {
                $image     = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);

                $existing = $ingredient->images()->first();
                if ($existing) {
                    $oldPath = public_path('images') . DIRECTORY_SEPARATOR . $existing->path;
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                    $existing->update(['path' => $imageName]);
                } else {
                    Image::create([
                        'path'           => $imageName,
                        'imageable_type' => Ingredient::class,
                        'imageable_id'   => $ingredient->id,
                    ]);
                }

            // If an image name string is provided (e.g., selecting from existing names), replace or create
            } elseif ($request->filled('image_name')) {
                $existing = $ingredient->images()->first();
                if ($existing) {
                    // Do not attempt to delete local file when using image_name (external or reference)
                    $existing->update(['path' => $request->image_name]);
                } else {
                    Image::create([
                        'path'           => $request->image_name,
                        'imageable_type' => Ingredient::class,
                        'imageable_id'   => $ingredient->id,
                    ]);
                }
            }
        };

        $data = [
            'name'            => $request->name,
            'category'        => $request->category,
            'unit'            => $request->unit,
            'current_stock'   => $request->current_stock,
            'minimum_stock'   => $request->minimum_stock,
            'cost_per_unit'   => $request->cost_per_unit,
            'expiration_date' => $request->expiration_date,
        ];

        // ✅ Update vs create is now properly separated from the status check
        if ($ingredientId) {
            Log::info('Attempting to update ingredient with ID: ' . $ingredientId);
            $ingredient = Ingredient::find($ingredientId);

            if (!$ingredient) {
                Log::error('Ingredient not found with ID: ' . $ingredientId);
                Log::error('All ingredients in database:', Ingredient::pluck('id', 'name')->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Ingrediente no encontrado con ID: ' . $ingredientId,
                ], 404);
            }

            $ingredient->update($data);
            $message = 'Ingrediente actualizado correctamente';
        } else {
            $ingredient = Ingredient::create($data);
            $message = 'Ingrediente agregado correctamente';
        }

        $imageHandler($ingredient);
        $ingredient->load('images');

        return response()->json([
            'success'    => true,
            'message'    => $message,
            'ingredient' => [
                'id'              => $ingredient->id,
                'name'            => $ingredient->name,
                'category'        => $ingredient->category,
                'unit'            => $ingredient->unit,
                'current_stock'   => (float) $ingredient->current_stock,
                'minimum_stock'   => (float) $ingredient->minimum_stock,
                'cost_per_unit'   => (float) ($ingredient->cost_per_unit ?? 0),
                'expiration_date' => $ingredient->expiration_date,
                'status'          => $status,
                'image'           => $ingredient->image,
            ],
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation errors:', $e->errors());
        Log::error('Failed request data:', $request->all());
        return response()->json([
            'success' => false,
            'message' => 'Error de validación: ' . json_encode($e->errors()),
            'errors'  => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al guardar el ingrediente: ' . $e->getMessage(),
        ], 500);
    }
}

    public function storeCategory(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:ingredients,category'
            ]);

            // Create a temporary ingredient with the new category so it appears in the list
            // This ingredient will have 0 stock and marked as inactive
            Ingredient::create([
                'name' => '_temp_' . $request->name,
                'category' => $request->name,
                'unit' => 'kg',
                'current_stock' => 0,
                'minimum_stock' => 0,
                'cost_per_unit' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Categoría creada correctamente'
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
                'message' => 'Error al crear la categoría: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyIngredient($id)
    {
        try {
            $ingredient = Ingredient::find($id);

            if (!$ingredient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ingrediente no encontrado'
                ], 404);
            }

            $ingredient->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ingrediente eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el ingrediente: ' . $e->getMessage()
            ], 500);
        }
    }

    public function recipes()
    {
        // Obtener recomendaciones de recetas
        $recommendationService = new RecipeRecommendationService();
        $recommendedRecipes = $recommendationService->getRecommendations(3);

        // Obtener todas las recetas reales con sus productos
        $recipes = Recipe::with('product')
            ->whereHas('ingredients')
            ->get()
            ->map(function ($recipe) {
                return [
                    'id' => $recipe->id,
                    'name' => $recipe->product->name ?? 'Receta #' . $recipe->id,
                    'slug' => \Illuminate\Support\Str::slug($recipe->product->name ?? 'receta-' . $recipe->id),
                    'category' => $this->getRecipeCategory($recipe->product->name ?? ''),
                    'description' => $this->getRecipeDescription($recipe->product->name ?? ''),
                    'time' => $this->getRecipeTime($recipe->product->name ?? ''),
                    'difficulty' => $this->getRecipeDifficulty($recipe->product->name ?? ''),
                ];
            });

        // Incluir recetas temporales de la sesión
        $tempRecipes = collect(session('temp_recipes', []))->map(function ($recipe) {
            return [
                'id' => $recipe['id'],
                'name' => $recipe['name'],
                'slug' => $recipe['slug'],
                'category' => $recipe['category'],
                'description' => 'Receta personalizada creada por ti.',
                'time' => 'Personalizado',
                'difficulty' => 'Personal',
            ];
        });

        $recipes = $recipes->merge($tempRecipes);

        return view('inventory.recipes', compact('recommendedRecipes', 'recipes'));
    }

    private function getRecipeCategory(string $name): string
    {
        $name = strtolower($name);
        if (str_contains($name, 'iced') || str_contains($name, 'cold') || str_contains($name, 'frappé') || str_contains($name, 'frappe')) {
            return 'Cafe Frio';
        }
        if (str_contains($name, 'croissant') || str_contains($name, 'muffin') || str_contains($name, 'cookie') || str_contains($name, 'pastel')) {
            return 'Postre';
        }
        return 'Cafe Caliente';
    }

    private function getRecipeDescription(string $name): string
    {
        $descriptions = [
            'iced americano' => 'Refrescante café helado con agua y espresso, perfecto para los días calurosos.',
            'iced latte' => 'Suave combinación de espresso helado con leche fría y hielo.',
            'frappé de café' => 'Bebida fría de café batido con hielo, ideal para el verano.',
            'cappuccino' => 'Clásico italiano con espresso, leche vaporizada y espuma cremosa.',
            'café latte' => 'Suave combinación de espresso con leche vaporizada y un toque de espuma.',
            'mocha' => 'Delicioso café con chocolate, leche vaporizada y crema batida.',
            'cold brew' => 'Café extraído en frío por 12 horas, suave y con bajo acidez.',
            'caramel macchiato' => 'Espresso con leche vaporizada, caramelo y vainilla.',
            'croissant' => 'Clásico pan francés hojaldrado y mantecoso.',
            'muffin' => 'Delicioso panecillo esponjoso perfecto para acompañar el café.',
        ];

        return $descriptions[strtolower($name)] ?? 'Receta de café artesanal preparada con ingredientes de calidad.';
    }

    private function getRecipeTime(string $name): string
    {
        $name = strtolower($name);
        if (str_contains($name, 'cold brew')) return '12 hr';
        if (str_contains($name, 'croissant') || str_contains($name, 'muffin')) return '45 min';
        return '5-8 min';
    }

    private function getRecipeDifficulty(string $name): string
    {
        $name = strtolower($name);
        if (str_contains($name, 'cold brew') || str_contains($name, 'croissant')) return 'Difícil';
        if (str_contains($name, 'cappuccino') || str_contains($name, 'macchiato') || str_contains($name, 'mocha')) return 'Medio';
        return 'Fácil';
    }

    public function createRecipe()
    {
        // Retornar vista vacía para nueva receta
        return view('inventory.recipes.show');
    }

    public function showRecipe($slug)
    {
        // Convertir slug a nombre de producto aproximado
        $searchName = str_replace('-', ' ', $slug);
        
        // Buscar receta por nombre de producto en la base de datos
        $recipe = Recipe::whereHas('product', function ($query) use ($searchName, $slug) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchName) . '%'])
                  ->orWhereRaw('LOWER(name) = ?', [strtolower($searchName)]);
        })->with(['product', 'ingredients.ingredient'])->first();
        
        // Si no encuentra, buscar en sesión temporal
        if (!$recipe) {
            $tempRecipes = session('temp_recipes', []);
            $recipeData = $tempRecipes[$slug] ?? null;
            
            if ($recipeData) {
                // Formatear ingredientes si es un array
                if (isset($recipeData['ingredients']) && is_array($recipeData['ingredients'])) {
                    $recipeData['ingredients'] = $this->formatIngredientsForDisplay($recipeData['ingredients']);
                }
                return view('inventory.recipes.show', ['recipe' => $recipeData]);
            }
        }

        if (!$recipe) {
            abort(404, 'Receta no encontrada');
        }

        $recipeData = [
            'id' => $recipe->id,
            'name' => $recipe->name,
            'slug' => $recipe->slug ?? \Illuminate\Support\Str::slug($recipe->product->name ?? $recipe->name ?? ''),
            'category' => $recipe->category,
            'image' => $recipe->image,
            'procedure' => $recipe->procedure,
            'ingredients' => $this->formatIngredientsForDisplay($recipe->ingredients->toArray()),
        ];

        return view('inventory.recipes.show', ['recipe' => $recipeData]);
    }

    public function storeRecipe(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'ingredients' => 'required|string',
            'procedure' => 'required|string',
        ]);

        // Simular creación de receta (reemplazar con modelo real)
        $newId = rand(1000, 9999);
        $slug = strtolower($request->name);
        $slug = str_replace(' ', '-', $slug);
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        $slug = preg_replace('/-+/', '-', $slug); // Reemplazar múltiples guiones por uno solo
        $slug = trim($slug, '-'); // Quitar guiones al inicio y final

        $recipe = [
            'id' => $newId,
            'name' => $request->name,
            'slug' => $slug,
            'category' => $request->category,
            'image' => null, // Por ahora null, puedes agregar lógica para imagen
            'ingredients' => $request->ingredients,
            'procedure' => $request->procedure,
        ];

        // Guardar en sesión temporal para que sea encontrada en showRecipe
        session(['temp_recipes' => array_merge(session('temp_recipes', []), [$slug => $recipe])]);

        return response()->json([
            'success' => true,
            'message' => 'Receta creada correctamente',
            'recipe' => $recipe,
            'redirect' => "/inventory/recipes/{$slug}"
        ]);
    }

    public function updateRecipe(Request $request, $slug)
    {
        try {
            // Buscar receta por slug
            $recipe = Recipe::where('slug', $slug)->first();
            
            if (!$recipe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Receta no encontrada'
                ], 404);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:50',
                'description' => 'nullable|string',
                'time' => 'nullable|string|max:50',
                'difficulty' => 'required|in:Fácil,Medio,Difícil',
                'ingredients' => 'nullable|array',
                'ingredients.*.name' => 'required|string|max:255',
                'ingredients.*.quantity' => 'required|numeric|min:0.1',
                'ingredients.*.unit' => 'required|string|max:50'
            ]);

            // Actualizar datos básicos de la receta
            $recipe->update([
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description,
                'time' => $request->time,
                'difficulty' => $request->difficulty,
            ]);

            // Actualizar ingredientes si se proporcionan
            if ($request->has('ingredients')) {
                // Eliminar ingredientes existentes
                $recipe->ingredients()->delete();
                
                // Agregar nuevos ingredientes
                foreach ($request->ingredients as $ingredientData) {
                    // Buscar o crear ingrediente
                    $ingredient = Ingredient::firstOrCreate(['name' => $ingredientData['name']]);
                    
                    // Crear relación receta-ingrediente
                    $recipe->ingredients()->create([
                        'ingredient_id' => $ingredient->id,
                        'quantity' => $ingredientData['quantity'],
                        'unit' => $ingredientData['unit']
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Receta actualizada correctamente',
                'recipe' => [
                    'id' => $recipe->id,
                    'name' => $recipe->name,
                    'slug' => $recipe->product ? \Illuminate\Support\Str::slug($recipe->product->name) : \Illuminate\Support\Str::slug($recipe->name),
                    'category' => $recipe->category,
                    'description' => $recipe->description,
                    'time' => $recipe->time,
                    'difficulty' => $recipe->difficulty,
                    'ingredients' => $recipe->ingredients->map(function ($ri) {
                        return $ri->ingredient ? $ri->ingredient->name : '';
                    })->filter()->implode("\n")
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
                'message' => 'Error al actualizar la receta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format ingredients array for textarea display
     */
    public function formatIngredientsForTextarea($ingredients)
    {
        if (!is_array($ingredients)) {
            return $ingredients;
        }
        
        $formatted = [];
        foreach ($ingredients as $ingredient) {
            if (isset($ingredient['ingredient'])) {
                $formatted[] = $ingredient['ingredient']['name'] . ': ' . $ingredient['quantity_required'] . ' ' . $ingredient['ingredient']['unit'];
            } elseif (isset($ingredient['name'])) {
                $formatted[] = $ingredient['name'] . ': ' . $ingredient['quantity'] . ' ' . $ingredient['unit'];
            }
        }

        return implode("\n", $formatted);
    }

    /**
     * Format ingredients array for display in view
     */
    public function formatIngredientsForDisplay($ingredients)
    {
        if (!is_array($ingredients)) {
            return $ingredients;
        }
        
        $formatted = [];
        foreach ($ingredients as $ingredient) {
            if (isset($ingredient['ingredient'])) {
                $formatted[] = [
                    'name' => $ingredient['ingredient']['name'],
                    'quantity' => $ingredient['quantity_required'],
                    'unit' => $ingredient['ingredient']['unit']
                ];
            }
        }
        
        return $formatted;
    }
}
