<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = [
        'patient_id', 'diagnosis', 'treatment', 'recorded_at',
    ];

    public function patient() {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
