<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedicineController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Medicine::with('category');

            // Optional filters
            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            if ($request->has('in_stock')) {
                $query->where('stock_quantity', '>', 0);
            }

            if ($request->has('not_expired')) {
                $query->where('expiry_date', '>', now());
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $medicines = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $medicines,
                'message' => 'Medicines retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve medicines',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new medicine
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'manufacturer' => 'nullable|string|max:255',
                'expiry_date' => 'required|date|after:today',
                'category_id' => 'required|exists:categories,id',
                'is_active' => 'boolean'
            ]);

            $medicine = Medicine::create($request->all());
            $medicine->load('category');

            return response()->json([
                'success' => true,
                'data' => $medicine,
                'message' => 'Medicine created successfully'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create medicine',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a specific medicine
     */
    public function show($id): JsonResponse
    {
        try {
            $medicine = Medicine::with('category')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $medicine,
                'message' => 'Medicine retrieved successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Medicine not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve medicine',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a medicine
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $medicine = Medicine::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'manufacturer' => 'nullable|string|max:255',
                'expiry_date' => 'required|date',
                'category_id' => 'required|exists:categories,id',
                'is_active' => 'boolean'
            ]);

            $medicine->update($request->all());
            $medicine->load('category');

            return response()->json([
                'success' => true,
                'data' => $medicine,
                'message' => 'Medicine updated successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Medicine not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update medicine',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a medicine
     */
    public function destroy($id): JsonResponse
    {
        try {
            $medicine = Medicine::findOrFail($id);
            $medicine->delete();

            return response()->json([
                'success' => true,
                'message' => 'Medicine deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Medicine not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete medicine',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get medicines by category
     */
    public function byCategory($categoryId): JsonResponse
    {
        try {
            $category = Category::findOrFail($categoryId);
            $medicines = Medicine::where('category_id', $categoryId)
                ->with('category')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'category' => $category->only(['id', 'name', 'description']),
                    'medicines' => $medicines
                ],
                'message' => 'Medicines by category retrieved successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve medicines by category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search medicines
     */
    public function search($term): JsonResponse
    {
        try {
            $medicines = Medicine::with('categories')
                ->where('name', 'LIKE', "%{$term}%")
                ->orWhere('description', 'LIKE', "%{$term}%")
                ->orWhere('manufacturer', 'LIKE', "%{$term}%")
                ->get();

            return response()->json([
                'success' => true,
                'data' => $medicines,
                'message' => 'Search results retrieved successfully',
                'search_term' => $term
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
