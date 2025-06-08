<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    protected $fillable = [
        'name', 'manufacturer', 'batch_number', 'quantity', 'price', 'expiry_date',
    ];

    public function sales() {
        return $this->hasMany(Sale::class);
    }

    public function purchases() {
        return $this->hasMany(Purchase::class);
    }
}
