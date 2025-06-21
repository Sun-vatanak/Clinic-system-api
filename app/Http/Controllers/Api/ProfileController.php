<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserProfile;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Get authenticated user's profile
     */
    public function getUserProfile()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Load profile with gender relationship - use the property access
            $profile = $user->profile; // This will automatically eager load if needed

            // If you need to explicitly load the gender relationship:
            if ($profile) {
                $profile->load('gender');
            }

            return response()->json([
                'success' => true,
                'profile' => $profile
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create or update user profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'gender_id' => 'required|exists:genders,id',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($user->profile && $user->profile->photo) {
                    Storage::delete('public/' . $user->profile->photo);
                }

                $path = $request->file('photo')->store('profile-photos', 'public');
                $validated['photo'] = $path;
            }

            // Update or create profile

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'profile' => $user->profile
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
