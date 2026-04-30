<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment() !== 'local') {
            $this->command->error('❌ Este seeder solo puede ejecutarse en entorno local');
            return;
        }

        if (Ingredient::query()->count() > 0) {
            $this->command->info('ℹ️  Ya existen ingredientes en la base de datos. Omitiendo seeder.');
            return;
        }

        $this->command->info('🌱 Creando ingredientes de demo...');

        $ingredients = [
            ['name' => 'Coffee Beans', 'unit' => 'kg', 'current_stock' => 10, 'minimum_stock' => 2, 'cost_per_unit' => 10.00],
            ['name' => 'Milk', 'unit' => 'l', 'current_stock' => 20, 'minimum_stock' => 5, 'cost_per_unit' => 1.50],
            ['name' => 'Sugar', 'unit' => 'kg', 'current_stock' => 5, 'minimum_stock' => 1, 'cost_per_unit' => 0.80],
            ['name' => 'Matcha Powder', 'unit' => 'kg', 'current_stock' => 2, 'minimum_stock' => 0.5, 'cost_per_unit' => 30.00],
            ['name' => 'Flour', 'unit' => 'kg', 'current_stock' => 8, 'minimum_stock' => 2, 'cost_per_unit' => 0.90],
            ['name' => 'Butter', 'unit' => 'kg', 'current_stock' => 3, 'minimum_stock' => 1, 'cost_per_unit' => 4.50],
            ['name' => 'Eggs', 'unit' => 'unit', 'current_stock' => 200, 'minimum_stock' => 30, 'cost_per_unit' => 0.10],
            ['name' => 'Chocolate', 'unit' => 'kg', 'current_stock' => 4, 'minimum_stock' => 1, 'cost_per_unit' => 12.00],
        ];

        foreach ($ingredients as $ing) {
            Ingredient::query()->create($ing);
        }

        $this->command->info('✅ Se crearon ' . count($ingredients) . ' ingredientes de demo correctamente');
    }
}
