<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'photo',
        'price',
        'stock_qty',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
