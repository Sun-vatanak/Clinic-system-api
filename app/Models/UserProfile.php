<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'address',
        'photo',
        'gender_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // In your User or Profile model
public function getPhotoUrlAttribute()
{
    return $this->photo ? url('/user-photos/'.$this->photo) : url('/default.png');
}
}
