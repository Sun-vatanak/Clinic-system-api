<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // One-to-Many relationship: Category has many medicines
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }

    // Get only active medicines for this category
    public function activeMedicines()
    {
        return $this->hasMany(Medicine::class)->where('is_active', true);
    }
}
