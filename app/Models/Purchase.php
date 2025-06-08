<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id', 'drug_id', 'quantity', 'cost_price', 'purchase_date',
    ];

    public function drug() {
        return $this->belongsTo(Drug::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
}
