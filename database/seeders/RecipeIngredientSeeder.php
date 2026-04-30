<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\RecipeIngredient;
use Illuminate\Database\Seeder;

class RecipeIngredientSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment() !== 'local') {
            $this->command->error('❌ Este seeder solo puede ejecutarse en entorno local');
            return;
        }

        $recipes = Recipe::all();
        $ingredients = Ingredient::all();

        if ($recipes->isEmpty() || $ingredients->isEmpty()) {
            $this->command->error('❌ Se necesitan recetas e ingredientes para crear asociaciones.');
            return;
        }

        $this->command->info('🌱 Asociando ingredientes a recetas...');

        foreach ($recipes as $recipe) {
            // Asociar los dos primeros ingredientes disponibles a cada receta
            $selected = $ingredients->take(2);
            foreach ($selected as $idx => $ing) {
                RecipeIngredient::updateOrCreate(
                    ['recipe_id' => $recipe->id, 'ingredient_id' => $ing->id],
                    ['quantity_required' => $idx === 0 ? 0.05 : 0.15]
                );
            }
        }

        $this->command->info('✅ Asociaciones creadas para ' . $recipes->count() . ' recetas');
    }
}
