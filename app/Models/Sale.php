<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'user_id', 'drug_id', 'quantity', 'total_price',
    ];

    public function drug() {
        return $this->belongsTo(Drug::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
