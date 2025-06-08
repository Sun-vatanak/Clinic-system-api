<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable
{
      use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role',
    ];

    protected $hidden = ['password'];

    // Relationships
    public function appointmentsAsPatient() {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function appointmentsAsDoctor() {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function medicalRecords() {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }

    public function sales() {
        return $this->hasMany(Sale::class);
    }

    public function invoices() {
        return $this->hasMany(Invoice::class);
    }

    public function notifications() {
        return $this->hasMany(Notification::class);
    }
}
