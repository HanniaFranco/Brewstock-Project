<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Solo ejecutar en entorno local para evitar afectar producción
        if (app()->environment() !== 'local') {
            $this->command->error('❌ Este seeder solo puede ejecutarse en entorno local');
            return;
        }

        // Verificar si ya existen productos para evitar duplicados
        if (Product::query()->count() > 0) {
            $this->command->info('ℹ️  Ya existen productos en la base de datos. Omitiendo seeder.');
            return;
        }

        $this->command->info('🌱 Creando productos de demo...');

        $products = [
            [
                'name' => 'Iced Americano',
                'category' => 'Bebidas Frías',
                'price' => 24.00,
                'status' => 'Activo',
                'image' => 'iced_americano.jpg',
                'active' => true,
            ],
            [
                'name' => 'Iced Latte',
                'category' => 'Bebidas Frías',
                'price' => 24.00,
                'status' => 'Activo',
                'image' => 'iced_latte.jpg',
                'active' => true,
            ],
            [
                'name' => 'Frappé de Café',
                'category' => 'Bebidas Frías',
                'price' => 24.00,
                'status' => 'Activo',
                'image' => 'frappe_cafe.jpg',
                'active' => true,
            ],
            [
                'name' => 'Iced Matcha Latte',
                'category' => 'Bebidas Frías',
                'price' => 24.00,
                'status' => 'Activo',
                'image' => 'iced_matcha_latte.jpg',
                'active' => true,
            ],
            [
                'name' => 'Café Americano',
                'category' => 'Bebidas calientes',
                'price' => 20.00,
                'status' => 'Activo',
                'image' => 'americano.jpg',
                'active' => true,
            ],
            [
                'name' => 'Café Latte',
                'category' => 'Bebidas calientes',
                'price' => 22.00,
                'status' => 'Activo',
                'image' => 'latte.jpg',
                'active' => true,
            ],
            [
                'name' => 'Cappuccino',
                'category' => 'Bebidas calientes',
                'price' => 22.00,
                'status' => 'Activo',
                'image' => 'cappuccino.jpg',
                'active' => true,
            ],
            [
                'name' => 'Té Verde',
                'category' => 'Tés e Infusiones',
                'price' => 18.00,
                'status' => 'Activo',
                'image' => 'te_verde.jpg',
                'active' => true,
            ],
            [
                'name' => 'Té Negro',
                'category' => 'Tés e Infusiones',
                'price' => 18.00,
                'status' => 'Activo',
                'image' => 'te_negro.jpg',
                'active' => true,
            ],
            [
                'name' => 'Croissant',
                'category' => 'Repostería',
                'price' => 15.00,
                'status' => 'Activo',
                'image' => 'croissant.jpg',
                'active' => true,
            ],
            [
                'name' => 'Muffin',
                'category' => 'Repostería',
                'price' => 12.00,
                'status' => 'Activo',
                'image' => 'muffin.jpg',
                'active' => true,
            ],
            [
                'name' => 'Mix de Frutos Secos',
                'category' => 'Snacks',
                'price' => 25.00,
                'status' => 'Activo',
                'image' => 'frutos_secos.jpg',
                'active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::query()->create($product);
        }

        $this->command->info('✅ Se crearon ' . count($products) . ' productos de demo correctamente');
    }
}
