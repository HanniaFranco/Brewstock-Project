<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Alert;
use App\Services\RecipeRecommendationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard principal
     */
    public function index()
    {
        // Obtener los productos más vendidos
        $bestSellingProducts = DB::table('sale_items as si')
            ->select(
                'p.id',
                'p.name',
                'p.price',
                DB::raw('SUM(si.quantity) as total_quantity'),
                DB::raw('SUM(si.quantity * si.price) as total_sales')
            )
            ->join('products as p', 'si.product_id', '=', 'p.id')
            ->groupBy('p.id', 'p.name', 'p.price')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        // Obtener el total de ventas del día
        $todaySales = Sale::whereDate('sale_date', today())->sum('total');

        // Obtener alertas no leídas
        $unreadAlerts = Alert::query()->where('is_read', false)->count();

        // Obtener últimas ventas
        $latestSales = Sale::with(['user', 'items.product'])
            ->latest('sale_date')
            ->limit(5)
            ->get();

        // Obtener recomendaciones de recetas
        $recommendationService = new RecipeRecommendationService();
        $recommendedRecipes = $recommendationService->getRecommendations(3);

        return view('dashboard.index', [
            'bestSellingProducts' => $bestSellingProducts,
            'todaySales' => $todaySales,
            'unreadAlerts' => $unreadAlerts,
            'latestSales' => $latestSales,
            'recommendedRecipes' => $recommendedRecipes,
            'user' => Auth::user(),
        ]);
    }
}
