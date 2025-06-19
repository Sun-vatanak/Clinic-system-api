<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'manufacturer',
        'expiry_date',
        'category_id',
        'is_active',
        'photo'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Many-to-One relationship: Medicine belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scope for active medicines
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for medicines that are not expired
    public function scopeNotExpired($query)
    {
        return $query->where('expiry_date', '>', now());
    }
}
