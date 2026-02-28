<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard principal
     */
    public function index()
    {
        // Obtener los productos más vendidos
        $bestSellingProducts = SaleItem::select(
            'products.id',
            'products.name',
            'products.price',
            DB::raw('SUM(sale_items.quantity) as total_quantity'),
            DB::raw('SUM(sale_items.quantity * sale_items.price) as total_sales')
        )
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        // Obtener el total de ventas del día
        $todaySales = Sale::whereDate('sale_date', today())
            ->sum('total');

        // Obtener alertas no leídas
        $unreadAlerts = Alert::where('is_read', false)
            ->count();

        // Obtener últimas ventas
        $latestSales = Sale::with(['user', 'items.product'])
            ->latest('sale_date')
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'bestSellingProducts' => $bestSellingProducts,
            'todaySales' => $todaySales,
            'unreadAlerts' => $unreadAlerts,
            'latestSales' => $latestSales,
            'user' => Auth::user(),
        ]);
    }
}
