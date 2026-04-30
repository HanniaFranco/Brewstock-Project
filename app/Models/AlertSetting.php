<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertSetting extends Model
{
    protected $table = 'alert_settings';

    protected $fillable = [
        'user_id',
        'product_id',
        'ingredient_id',
        'threshold',
        'enabled',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
