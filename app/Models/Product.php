<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'price',
        'category',
        'active',
    ];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

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

    public function getImageAttribute()
    {
        return $this->images->first()?->path;
    }
}
