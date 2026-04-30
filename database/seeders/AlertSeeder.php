<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;

class AlertSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment() !== 'local') {
            $this->command->error('❌ Este seeder solo puede ejecutarse en entorno local');
            return;
        }

        if (Alert::query()->count() > 0) {
            $this->command->info('ℹ️  Ya existen alertas en la base de datos. Omitiendo seeder.');
            return;
        }

        $ingredient = Ingredient::first();
        $product = Product::first();

        if (! $ingredient && ! $product) {
            $this->command->error('❌ No hay ingredientes ni productos para crear alertas.');
            return;
        }

        $this->command->info('🌱 Creando alertas de demo...');

        if ($ingredient) {
            Alert::create([
                'ingredient_id' => $ingredient->id,
                'type' => 'low_stock',
                'message' => 'Stock bajo: ' . $ingredient->name,
                'is_read' => false,
            ]);
        }

        if ($product) {
            Alert::create([
                'product_id' => $product->id,
                'type' => 'out_of_stock',
                'message' => 'Producto fuera de stock: ' . $product->name,
                'is_read' => false,
            ]);
        }

        $this->command->info('✅ Alertas de demo creadas');
    }
}
