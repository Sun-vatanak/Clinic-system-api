<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'phone',
        'photo',
        'password',
        'role_id',
        'gender_id',
        'first_name',
        'last_name',
        'telegram_id',

        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

  
    public function profile()
{
    return $this->hasOne(UserProfile::class);
}
    // In UserController
public function activate(User $user)
{
    $user->update(['is_active' => true]);
    return response()->json(['result' => true, 'message' => 'User activated successfully']);
}

public function deactivate(User $user)
{
    $user->update(['is_active' => false]);
    return response()->json([
        'result' => true,
        'message' => 'User deactivated successfully'
    ]);
}
}
