<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\InventoryMovement;
use App\Models\Ingredient;

class SalesController extends Controller
{
    public function index()
    {
        return view('sales.index');
    }

    public function create()
    {
        $products = Product::where('active', 1)->orderBy('name')->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        // The view posts `items` as a JSON string. Decode and validate manually.
        $total = $request->input('total');
        $paid = $request->boolean('paid');
        $itemsJson = $request->input('items', '[]');
        $items = json_decode($itemsJson, true);

        if (!is_array($items) || count($items) === 0) {
            return back()->withErrors(['items' => 'No hay productos en la venta.'])->withInput();
        }

        foreach ($items as $idx => $it) {
            if (!isset($it['product_id']) || !is_numeric($it['product_id'])) {
                return back()->withErrors(['items' => "Item #{$idx} inválido: falta product_id"])->withInput();
            }
            if (!isset($it['quantity']) || !is_numeric($it['quantity']) || $it['quantity'] <= 0) {
                return back()->withErrors(['items' => "Item #{$idx} inválido: cantidad"])->withInput();
            }
        }

        if (!is_numeric($total)) {
            return back()->withErrors(['total' => 'Total inválido'])->withInput();
        }

        $data = [
            'items' => $items,
            'total' => (float) $total,
            'paid' => (bool) $paid,
        ];

        DB::beginTransaction();
        try {
            $sale = Sale::create([
                'user_id' => $request->user()->id,
                'total' => $data['total'],
                'sale_date' => now(),
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);

                if ($data['paid']) {
                    // Subtract ingredients according to product recipe
                    if ($product->recipe) {
                        foreach ($product->recipe->ingredients as $ri) {
                            $used = $ri->quantity_required * $quantity;
                            $ingredient = Ingredient::find($ri->ingredient_id);
                            if ($ingredient) {
                                $ingredient->current_stock = max(0, $ingredient->current_stock - $used);
                                $ingredient->save();

                                InventoryMovement::create([
                                    'ingredient_id' => $ingredient->id,
                                    'type' => 'out',
                                    'quantity' => $used,
                                    'reason' => 'Venta #'.$sale->id.' - Producto: '.$product->name,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Venta registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al procesar la venta: '.$e->getMessage()]);
        }
    }
}
