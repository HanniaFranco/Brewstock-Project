<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return view('inventory.index');
    }

    public function ingredients()
    {
        // Datos de ejemplo para ingredientes
        $allIngredients = [
            [
                'id' => 1,
                'name' => 'Café molido',
                'unit' => 'kg',
                'current_stock' => 15.50,
                'minimum_stock' => 5.00,
                'cost_per_unit' => 120.00,
                'expiration_date' => '2024-12-31',
                'status' => 'stock_ok'
            ],
            [
                'id' => 2,
                'name' => 'Leche entera',
                'unit' => 'L',
                'current_stock' => 8.00,
                'minimum_stock' => 10.00,
                'cost_per_unit' => 25.00,
                'expiration_date' => '2024-11-15',
                'status' => 'low_stock'
            ],
            [
                'id' => 3,
                'name' => 'Azúcar',
                'unit' => 'kg',
                'current_stock' => 25.00,
                'minimum_stock' => 10.00,
                'cost_per_unit' => 35.00,
                'expiration_date' => null,
                'status' => 'stock_ok'
            ],
            [
                'id' => 4,
                'name' => 'Jarabe de vainilla',
                'unit' => 'L',
                'current_stock' => 2.50,
                'minimum_stock' => 3.00,
                'cost_per_unit' => 85.00,
                'expiration_date' => '2024-10-30',
                'status' => 'low_stock'
            ],
            [
                'id' => 5,
                'name' => 'Chocolate en polvo',
                'unit' => 'kg',
                'current_stock' => 8.00,
                'minimum_stock' => 5.00,
                'cost_per_unit' => 95.00,
                'expiration_date' => '2025-01-15',
                'status' => 'stock_ok'
            ],
            [
                'id' => 6,
                'name' => 'Canela en rama',
                'unit' => 'kg',
                'current_stock' => 0.50,
                'minimum_stock' => 1.00,
                'cost_per_unit' => 150.00,
                'expiration_date' => '2024-09-20',
                'status' => 'critical_stock'
            ],
        ];

        return view('inventory.ingredients', compact('allIngredients'));
    }

    public function storeIngredient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
        ]);

        // Calcular estado basado en el stock
        $currentStock = $request->current_stock;
        $minimumStock = $request->minimum_stock;
        
        if ($currentStock == 0) {
            $status = 'critical_stock';
        } elseif ($currentStock < $minimumStock) {
            $status = 'low_stock';
        } else {
            $status = 'stock_ok';
        }

        // Simular creación (reemplazar con modelo real)
        $newId = rand(100, 999);
        $ingredient = [
            'id' => $newId,
            'name' => $request->name,
            'unit' => $request->unit,
            'current_stock' => (float) $request->current_stock,
            'minimum_stock' => (float) $request->minimum_stock,
            'cost_per_unit' => (float) $request->cost_per_unit,
            'expiration_date' => $request->expiration_date,
            'status' => $status,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Ingrediente agregado correctamente',
            'ingredient' => $ingredient
        ]);
    }

    public function recipes()
    {
        return view('inventory.recipes');
    }

    public function createRecipe()
    {
        // Retornar vista vacía para nueva receta
        return view('inventory.recipes.show');
    }

    public function showRecipe($slug)
    {
        // Datos de ejemplo para recetas (simular búsqueda por slug)
        $recipes = [
            'iced-americano' => [
                'id' => 1,
                'name' => 'Iced Americano',
                'slug' => 'iced-americano',
                'category' => 'bebida-fria',
                'image' => null,
                'ingredients' => "Espresso Doble\nAgua Filtrada Fría\nHielo",
                'procedure' => "1. Prepara un espresso doble y déjalo enfriar por unos minutos.\n\n2. Llena un vaso grande con hielo hasta las 3/4 partes.\n\n3. Vierte el espresso enfriado sobre el hielo.\n\n4. Agrega agua filtrada fría hasta llenar el vaso.\n\n5. Revuelve suavemente y sirve inmediatamente.\n\n6. Opcional: puedes añadir leche o edulcorante al gusto."
            ],
            'cappuccino' => [
                'id' => 2,
                'name' => 'Cappuccino',
                'slug' => 'cappuccino',
                'category' => 'bebida-caliente',
                'image' => null,
                'ingredients' => "Espresso Doble\nLeche entera\nEspuma de leche\nCanela (opcional)",
                'procedure' => "1. Prepara un espresso doble.\n\n2. Calienta la leche a 65°C mientras creas espuma con el vaporizador.\n\n3. Vierte el espresso en una taza.\n\n4. Añade la leche caliente con espuma.\n\n5. Espolvorea canela si lo deseas.\n\n6. Sirve inmediatamente."
            ],
            'cafe-latte' => [
                'id' => 3,
                'name' => 'Café Latte',
                'slug' => 'cafe-latte',
                'category' => 'bebida-caliente',
                'image' => null,
                'ingredients' => "Espresso Sencillo\nLeche vaporizada\nPequeña cantidad de espuma",
                'procedure' => "1. Prepara un espresso sencillo.\n\n2. Vaporiza la leche hasta que esté caliente y suave.\n\n3. Vierte el espresso en una taza alta.\n\n4. Añade la leche vaporizada lentamente.\n\n5. Corona con un poco de espuma.\n\n6. Sirve caliente."
            ]
        ];

        // Buscar primero en las recetas estáticas
        $recipe = $recipes[$slug] ?? null;

        // Si no encuentra, buscar en la sesión temporal
        if (!$recipe) {
            $tempRecipes = session('temp_recipes', []);
            $recipe = $tempRecipes[$slug] ?? null;
        }

        if (!$recipe) {
            abort(404, 'Receta no encontrada');
        }

        return view('inventory.recipes.show', compact('recipe'));
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
        $request->validate([
            'category' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'ingredients' => 'required|string',
            'procedure' => 'required|string',
        ]);

        // Simular actualización (reemplazar con modelo real)
        $recipe = [
            'id' => 1, // Simular ID existente
            'name' => $request->name,
            'slug' => $slug,
            'category' => $request->category,
            'image' => null,
            'ingredients' => $request->ingredients,
            'procedure' => $request->procedure,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Receta actualizada correctamente',
            'recipe' => $recipe
        ]);
    }
}
