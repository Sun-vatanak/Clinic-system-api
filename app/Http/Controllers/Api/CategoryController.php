<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // Get paginated list of categories
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'sort_by' => 'nullable|in:id,name,created_at',
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

        $query = Category::withCount('medicines')
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            })
            ->when($request->has('is_active'), function ($q, $isActive) {
                $q->where('is_active', $isActive);
            });

        $sortBy = $request->sort_by ?? 'created_at';
        $sortDir = $request->sort_dir ?? 'desc';
        $perPage = $request->per_page ?? 10;

        $categories = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        return response()->json([
            'result' => true,
            'message' => 'Categories retrieved successfully',
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ],
            'data' => CategoryResource::collection($categories),
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:4048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }


        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('category-photos', 'public');
        }

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'photo' => $photoPath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'result' => true,
            'message' => 'Category created successfully',
            'data' => new CategoryResource($category)
        ], 201);
    }

    // Get single category
    public function show(Category $category)
    {
        return response()->json([
            'result' => true,
            'message' => 'Category retrieved successfully',
            'data' => new CategoryResource($category->load('medicines'))
        ]);
    }

    // Update category with photo handling
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id)
            ],
            'description' => 'nullable|string',
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
        $photoPath = $category->photo;
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('category-photos', 'public');
        } elseif ($request->boolean('remove_photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = null;
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'photo' => $photoPath,
            'is_active' => $request->boolean('is_active', $category->is_active),
        ]);

        return response()->json([
            'result' => true,
            'message' => 'Category updated successfully',
            'data' => new CategoryResource($category)
        ]);
    }

    // Delete category with photo cleanup
public function destroy($id)
{
    $category = Category::findOrFail($id);

    // Delete photo if exists
    if ($category->photo && Storage::disk('public')->exists($category->photo)) {
        Storage::disk('public')->delete($category->photo);
    }

    $category->delete();

    return response()->json([
        'result' => true,
        'message' => 'Category deleted successfully'
    ]);
}

    // Activate category
    public function activate(Category $category)
    {
        $category->update(['is_active' => true]);

        return response()->json([
            'result' => true,
            'message' => 'Category activated successfully',
            'data' => new CategoryResource($category)
        ]);
    }

    // Deactivate category
    public function deactivate(Category $category)
    {
        $category->update(['is_active' => false]);

        return response()->json([
            'result' => true,
            'message' => 'Category deactivated successfully',
            'data' => new CategoryResource($category)
        ]);
    }

    // Get category medicines
    public function medicines(Category $category)
    {
        return response()->json([
            'result' => true,
            'message' => 'Category medicines retrieved successfully',
            'data' => [
                'category' => new CategoryResource($category),
                'medicines' => $category->medicines
            ]
        ]);
    }
}
