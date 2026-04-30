<?php

namespace Database\Seeders;

use App\Models\InventoryMovement;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class InventoryMovementSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment() !== 'local') {
            $this->command->error('❌ Este seeder solo puede ejecutarse en entorno local');
            return;
        }

        if (InventoryMovement::query()->count() > 0) {
            $this->command->info('ℹ️  Ya existen movimientos en la base de datos. Omitiendo seeder.');
            return;
        }

        $ingredient = Ingredient::first();
        if (! $ingredient) {
            $this->command->error('❌ No hay ingredientes para crear movimientos.');
            return;
        }

        InventoryMovement::create([
            'ingredient_id' => $ingredient->id,
            'type' => 'in',
            'quantity' => 5,
            'reason' => 'Stock inicial de demo',
        ]);

        InventoryMovement::create([
            'ingredient_id' => $ingredient->id,
            'type' => 'out',
            'quantity' => 0.2,
            'reason' => 'Uso en producto de demo',
        ]);

        $this->command->info('✅ Movimientos de inventario de demo creados');
    }
}
