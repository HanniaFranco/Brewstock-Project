<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $table = 'Ingredient';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'unit',
        'current_stock',
        'minimum_stock',
        'expiration_date',
        'cost_per_unit',
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
