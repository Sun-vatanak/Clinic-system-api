<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Get paginated list of users
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'role_id' => 'nullable|integer|exists:roles,id',
            'status' => 'nullable|in:active,inactive',
            'sort_by' => 'nullable|in:id,email,created_at',
            'sort_dir' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = User::with(['profile', 'role'])
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('email', 'like', "%$search%")
                        ->orWhereHas('profile', function ($q) use ($search) {
                            $q->where('first_name', 'like', "%$search%")
                                ->orWhere('last_name', 'like', "%$search%")
                                ->orWhere('phone', 'like', "%$search%");
                        });
                });
            })
            ->when($request->role_id, fn ($q, $roleId) => $q->where('role_id', $roleId))
            ->when($request->status, function ($q, $status) {
                $q->where('is_active', $status === 'active' ? 1 : 0);
            });

        $sortBy = $request->sort_by ?? 'created_at';
        $sortDir = $request->sort_dir ?? 'desc';
        $perPage = $request->per_page ?? 15;

        $users = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        return response()->json([
            'result' => true,
            'message' => 'Users retrieved successfully',
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    // Create a new user
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'first_name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'phone' => 'required|string|max:20|unique:user_profiles,phone',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle file upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('user-photos', 'public');
        }

        // Create user
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Create profile
        $user->profile()->create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address,
            'photo' => $photoPath,
        ]);

        return response()->json([
            'result' => true,
            'message' => 'User created successfully',
            'data' => new UserResource($user->load(['profile', 'role']))
        ], 201);
    }

    // Get single user
    public function show(User $user)
    {
        return response()->json([
            'result' => true,
            'message' => 'User retrieved successfully',
            'data' => new UserResource($user->load(['profile', 'role']))
        ]);
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required','email',Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
            'first_name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'phone' => ['required','string','max:20',Rule::unique('user_profiles','phone')->ignore($user->id, 'user_id')],
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_photo' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle photo update
        $photoPath = $user->profile->photo ?? null;
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('user-photos', 'public');
        } elseif ($request->boolean('remove_photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = null;
        }

        // Update user
        $user->update([
            'email' => $request->email,
            'role_id' => $request->role_id,
            'is_active' => $request->boolean('is_active', $user->is_active),
        ]);

        // Update profile
        $user->profile()->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address,
            'photo' => $photoPath,
        ]);

        return response()->json([
            'result' => true,
            'message' => 'User updated successfully',
            'data' => new UserResource($user->load(['profile', 'role']))
        ]);
    }

    // Delete user
    public function destroy(User $user)
    {
        // Delete photo if exists
        if ($user->profile->photo && Storage::disk('public')->exists($user->profile->photo)) {
            Storage::disk('public')->delete($user->profile->photo);
        }

        $user->delete();

        return response()->json([
            'result' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    // Activate user
    public function activate(User $user)
    {
        $user->update(['is_active' => true]);

        return response()->json([
            'result' => true,
            'message' => 'User activated successfully',
            'data' => new UserResource($user->load(['profile', 'role']))
        ]);
    }

    // Deactivate user
    public function deactivate(User $user)
    {
        $user->update(['is_active' => false]);

        return response()->json([
            'result' => true,
            'message' => 'User deactivated successfully',
            'data' => new UserResource($user->load(['profile', 'role']))
        ]);
    }

    // Change password
    public function changePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'result' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    // Get user statistics
    public function statistics()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $newUsersToday = User::whereDate('created_at', Carbon::today())->count();

        return response()->json([
            'result' => true,
            'message' => 'Statistics retrieved successfully',
            'data' => [
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'new_users_today' => $newUsersToday,
            ]
        ]);
    }
}
