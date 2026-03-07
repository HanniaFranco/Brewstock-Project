<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $table = 'InventoryMovement';

    public $timestamps = false;

    protected $fillable = [
        'ingredient_id',
        'type',
        'quantity',
        'reason',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }


}
