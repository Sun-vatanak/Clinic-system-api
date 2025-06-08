<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        return User::all();
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => 'required|min:6',
        ]);

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
    }

    public function show(User $user) {
        return $user;
    }

    public function update(Request $request, User $user) {
        
        $user->update($request->only(['name', 'email', 'phone', 'role']));
        return $user;
    }

    public function destroy(User $user) {
        $user->delete();
        return response()->noContent();
    }
}
