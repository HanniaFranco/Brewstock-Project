<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
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
        $bestSellingProducts = DB::table('SaleItem as si')
            ->select(
                'p.id',
                'p.name',
                'p.price',
                DB::raw('SUM(si.quantity) as total_quantity'),
                DB::raw('SUM(si.quantity * si.price) as total_sales')
            )
            ->join('Products as p', 'si.product_id', '=', 'p.id')
            ->groupBy('p.id', 'p.name', 'p.price')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        // Obtener el total de ventas del día
        $todaySales = Sale::whereDate('sale_date', today())
            ->sum('total');

        // Obtener alertas no leídas
        $unreadAlerts = Alert::whereIn(DB::raw('LOWER(COALESCE(status, ""))'), ['pendiente', 'pending', 'unread'])
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
