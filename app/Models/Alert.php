<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    //
    protected $fillable = [
        'ingredient_id',
        'type',
        'message',
        'status',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }


}
