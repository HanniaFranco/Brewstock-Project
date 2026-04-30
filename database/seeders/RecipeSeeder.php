<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\Product;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment() !== 'local') {
            $this->command->error('❌ Este seeder solo puede ejecutarse en entorno local');
            return;
        }

        if (Recipe::query()->count() > 0) {
            $this->command->info('ℹ️  Ya existen recetas en la base de datos. Omitiendo seeder.');
            return;
        }

        $products = Product::query()->get();
        if ($products->isEmpty()) {
            $this->command->error('❌ No hay productos para crear recetas. Ejecuta ProductSeeder primero.');
            return;
        }

        foreach ($products as $product) {
            Recipe::query()->create(['product_id' => $product->id]);
        }

        $this->command->info('✅ Se crearon ' . $products->count() . ' recetas (una por producto)');
    }
}
