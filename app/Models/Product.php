<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'img', 'description'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function currentPrice()
    {
        return $this->hasOne(Price::class)
            ->where('start_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }
}

