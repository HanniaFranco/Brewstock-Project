<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Ingredient extends Model
{
    protected $table = 'ingredients';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'category',
        'unit',
        'current_stock',
        'minimum_stock',
        'expiration_date',
        'cost_per_unit',
    ];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

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

    public function getImageAttribute()
    {
        return $this->images->first()?->path;
    }
}
