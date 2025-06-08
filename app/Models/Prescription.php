<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'appointment_id', 'medication_details', 'instructions',
    ];

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }
}
