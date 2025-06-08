<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        return Supplier::all();
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
        ]);

        return Supplier::create($request->all());
    }

    public function show(Supplier $supplier) {
        return $supplier;
    }

    public function update(Request $request, Supplier $supplier) {
        $supplier->update($request->all());
        return $supplier;
    }

    public function destroy(Supplier $supplier) {
        $supplier->delete();
        return response()->noContent();
    }
}
