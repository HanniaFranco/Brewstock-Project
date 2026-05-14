<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\AlertSetting;
use Illuminate\Support\Facades\Auth;

class AlertsController extends Controller
{
    public function index()
    {
        return view('alerts.index');
    }

    public function settings()
    {
        // Obtener ingredientes reales (excluir temporales usados para categorías)
        $ingredients = Ingredient::whereNotNull('id')
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->filter(function($i){
                return !\str_starts_with($i->name, '_temp_');
            });

        // Agrupar por categoría, colocando 'Sin categoría' al final si existe
        $grouped = $ingredients->groupBy(function($i){
            return $i->category ?? 'Sin categoría';
        })->sortKeys();

        if ($grouped->has(null) || $grouped->has('')) {
            $noCat = $grouped->pull(null) ?? $grouped->pull('') ?? collect();
            $grouped['Sin categoría'] = $noCat;
        }

        $settings = AlertSetting::where('user_id', Auth::id())->get()->keyBy(function($s){
            return 'i_'.$s->ingredient_id;
        });

        return view('alerts.settings', ['groupedIngredients' => $grouped, 'settings' => $settings]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ingredient' => ['array'],
            'ingredient.*.id' => ['integer','nullable'],
            'ingredient.*.threshold' => ['nullable'],
        ]);

        $userId = Auth::id();

        foreach ($request->input('ingredient', []) as $i) {
            if (empty($i['id'])) continue;
            $threshold = floatval($i['threshold'] ?? 0);
            AlertSetting::updateOrCreate(
                ['user_id' => $userId, 'ingredient_id' => $i['id']],
                ['threshold' => $threshold, 'enabled' => $threshold > 0]
            );
        }

        return redirect()->route('alerts.settings')->with('success', 'Configuración de alertas guardada.');
    }

    public function unread()
    {
        $userId = Auth::id();
        $alerts = \App\Models\Alert::where('user_id', $userId)->where('is_read', false)->orderByDesc('created_at')->get();
        return response()->json($alerts);
    }

    public function markRead($id)
    {
        $alert = \App\Models\Alert::findOrFail($id);
        $alert->is_read = true;
        $alert->read_at = now();
        $alert->save();
        return response()->json(['success' => true]);
    }
}
