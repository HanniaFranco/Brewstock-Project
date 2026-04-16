<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $table = 'ingredients';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'category',
        'cost_per_unit',
        'unit',
        'current_stock',
        'minimum_stock',
        'expiration_date',
        'image',
        'status'
    ];

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function recipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }


}
