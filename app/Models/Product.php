<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'price',
        'category',
        'image',
        'active',
    ];

    public function recipe()
    {
        return $this->hasOne(Recipe::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getStatusAttribute()
    {
        return $this->active ? 'Activo' : 'Inactivo';
    }

    public function setStatusAttribute($value)
    {
        $this->active = $value === 'Activo';
    }

}
