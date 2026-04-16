<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ingredient::query();

        // BUSCADOR
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // FILTRO POR CATEGORÍA
        if ($request->category) {
            $query->where('category', $request->category);
        }

        $ingredients = $query->get();

        return view('ingredients.index', compact('ingredients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ingredients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $imagePath = $request->file('image') 
        ? $request->file('image')->store('ingredients', 'public') 
        : null;

        Ingredient::create([
            'name' => $request->name,
            'category' => $request->category,
            'cost_per_unit' => $request->price, 
            'unit' => $request->unit ?? 'unidad',
            'current_stock' => $request->stock ?? 0,
            'minimum_stock' => 0,
            'expiration_date' => null,
            'image' => $imagePath,
            'status' => $request->status ?? 'activo'
        ]);

            return redirect()->route('ingredients.index')
        ->with('success', 'Ingrediente agregado correctamente');

    }

    public function export()
    {
        $ingredients = Ingredient::all();

        $pdf = Pdf::loadView('ingredients.pdf', compact('ingredients'));

        return $pdf->download('ingredientes.pdf');
    }

    public function toggle($id)
    {
        $ingredient = Ingredient::findOrFail($id);

        $ingredient->status = $ingredient->status == 'activo' ? 'inactivo' : 'activo';
        $ingredient->save();

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        return view('ingredients.show', compact('ingredient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        return view('ingredients.edit', compact('ingredient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ingredient = Ingredient::findOrFail($id);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ingredients', 'public');
            $ingredient->image = $imagePath;
        }

        $ingredient->update([
            'name' => $request->name,
            'category' => $request->category,
            'cost_per_unit' => $request->price,
            'unit' => $request->unit,
            'current_stock' => $request->stock,
            'status' => $request->status,
        ]);

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingrediente actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();

        return redirect()->route('ingredients.index')
            ->with('success', 'Ingrediente eliminado correctamente');
        }

}
